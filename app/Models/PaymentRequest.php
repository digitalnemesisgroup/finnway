<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_client_id',
        'client_order_id',
        'amount',
        'currency',
        'customer_phone',
        'customer_email',
        'return_url',
        'webhook_url',
        'cashfree_order_id',
        'cashfree_session_id',
        'payment_status',
        'transaction_id',
        'webhook_dispatched',
        'session_token',
        'open_count',
        'max_opens',
        'expires_at',
        'closed_at',
    ];

    protected function casts(): array
    {
        return [
            'amount'              => 'decimal:2',
            'webhook_dispatched'  => 'boolean',
            'expires_at'          => 'datetime',
            'closed_at'           => 'datetime',
        ];
    }

    public function isSessionExpired(): bool
    {
        return $this->expires_at && now()->greaterThan($this->expires_at);
    }

    public function hasExceededMaxOpens(): bool
    {
        return $this->open_count > ($this->max_opens ?? 2);
    }

    public function isSessionClosed(): bool
    {
        return !is_null($this->closed_at) || in_array($this->payment_status, ['user_dropped', 'failed', 'rejected', 'cancelled']);
    }

    public function client()
    {
        return $this->belongsTo(PaymentClient::class, 'payment_client_id');
    }

    /**
     * Build the signed payload to POST to the client's webhook_url
     * after payment is confirmed/failed/dropped.
     */
    public function buildCallbackPayload(): array
    {
        return [
            'client_order_id' => $this->client_order_id,
            'status'          => $this->payment_status,  // success | failed | user_dropped
            'transaction_id'  => $this->transaction_id,
            'amount'          => (string) $this->amount,
            'currency'        => $this->currency,
        ];
    }

    /**
     * Generate HMAC signature for outgoing callback so the receiving app
     * can verify it truly came from our Payment Hub.
     */
    public function buildCallbackSignature(array $payload): string
    {
        return hash_hmac('sha256', json_encode($payload, JSON_UNESCAPED_UNICODE), $this->client->api_salt);
    }

    /**
     * Dispatch outgoing webhook notification to the client app's webhook_url.
     */
    public function fireClientCallback(): bool
    {
        if ($this->webhook_dispatched || !$this->webhook_url) {
            return true;
        }

        $payload   = $this->buildCallbackPayload();
        $rawJson   = json_encode($payload);
        $signature = hash_hmac('sha256', $rawJson, $this->client->api_salt);

        try {
            $response = \Illuminate\Support\Facades\Http::timeout(10)
                ->withHeaders([
                    'X-Hub-Signature' => $signature,
                    'Content-Type'    => 'application/json',
                ])
                ->withBody($rawJson, 'application/json')
                ->post($this->webhook_url);

            if ($response->successful()) {
                $this->update(['webhook_dispatched' => true]);
                \Illuminate\Support\Facades\Log::info("PaymentRequest #{$this->id}: Client webhook callback delivered to [{$this->webhook_url}]");
                return true;
            } else {
                \Illuminate\Support\Facades\Log::warning("PaymentRequest #{$this->id}: Client webhook callback returned HTTP {$response->status()}");
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("PaymentRequest #{$this->id}: Client webhook callback exception: " . $e->getMessage());
        }

        return false;
    }
}
