<?php

namespace App\Services;

use App\Models\AppSetting;
use App\Models\Payment;
use App\Models\PaymentRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentSessionService
{
    /**
     * Create an encrypted session for a Payment or PaymentRequest model.
     * Stores max_opens, expires_at, and session_token in DB & Cache.
     */
    public function createSession($model, string $type = 'hub'): string
    {
        $maxOpens = (int) AppSetting::get('payment_session_max_opens', 2);
        $ttlMinutes = (int) AppSetting::get('payment_session_ttl_minutes', 15);
        $expiresAt = now()->addMinutes($ttlMinutes);

        $payload = [
            'id'          => $model->id,
            'type'        => $type, // 'hub' or 'buyer'
            'max_opens'   => $maxOpens,
            'created_at'  => now()->timestamp,
            'expires_at'  => $expiresAt->timestamp,
            'nonce'       => Str::random(16),
        ];

        $token = Crypt::encryptString(json_encode($payload));

        $model->update([
            'session_token' => $token,
            'open_count'    => 0,
            'max_opens'     => $maxOpens,
            'expires_at'    => $expiresAt,
            'closed_at'     => null,
        ]);

        // Cache initial state
        $cacheKey = "payment_session_{$type}_{$model->id}";
        Cache::put($cacheKey, [
            'open_count' => 0,
            'max_opens'  => $maxOpens,
            'expires_at' => $expiresAt->timestamp,
            'status'     => 'active',
        ], $ttlMinutes * 60);

        Cache::put("payment_session_opens_{$type}_{$model->id}", 0, $ttlMinutes * 60);

        return $token;
    }

    /**
     * Verify and increment the session for an incoming request.
     * Decrypts the token, checks TTL, checks max open count (DB + Cache), and updates count.
     */
    public function verifyAndIncrementSession(string $token, string $expectedType = 'hub'): array
    {
        try {
            $json = Crypt::decryptString($token);
            $payload = json_decode($json, true);
        } catch (\Throwable $e) {
            Log::warning("PaymentSessionService: Invalid or un-decryptable session token: " . $e->getMessage());
            return [
                'valid'   => false,
                'reason'  => 'invalid_token',
                'message' => 'Payment session link is invalid or corrupted.',
            ];
        }

        if (!$payload || !isset($payload['id'], $payload['type'], $payload['expires_at'])) {
            return [
                'valid'   => false,
                'reason'  => 'invalid_payload',
                'message' => 'Payment session payload format is invalid.',
            ];
        }

        $id   = $payload['id'];
        $type = $payload['type'];

        if ($type !== $expectedType) {
            return [
                'valid'   => false,
                'reason'  => 'type_mismatch',
                'message' => 'Payment session type mismatch.',
            ];
        }

        // Check TTL expiration
        if (now()->timestamp > $payload['expires_at']) {
            $this->rejectSession($id, $type, 'expired');
            return [
                'valid'   => false,
                'reason'  => 'expired',
                'message' => 'Payment session has expired (TTL timeout).',
            ];
        }

        // Resolve Model
        $model = ($type === 'hub')
            ? PaymentRequest::with('client')->find($id)
            : Payment::with('order')->find($id);

        if (!$model) {
            return [
                'valid'   => false,
                'reason'  => 'not_found',
                'message' => 'Payment request record not found.',
            ];
        }

        // If payment is already completed or rejected/cancelled
        $status = ($type === 'hub') ? $model->payment_status : $model->status;
        if (in_array($status, ['success', 'paid', 'failed', 'user_dropped', 'rejected', 'cancelled'])) {
            return [
                'valid'   => false,
                'reason'  => 'terminal_state',
                'status'  => $status,
                'model'   => $model,
                'message' => "Payment session is already in final state: {$status}.",
            ];
        }

        if ($model->isSessionExpired()) {
            $this->rejectSession($id, $type, 'expired');
            return [
                'valid'   => false,
                'reason'  => 'expired',
                'model'   => $model,
                'message' => 'Payment session has expired (TTL timeout).',
            ];
        }

        $maxOpens = $model->max_opens ?? (int) AppSetting::get('payment_session_max_opens', 2);

        // Atomic Cache Open Counter
        $cacheOpensKey = "payment_session_opens_{$type}_{$id}";
        if (!Cache::has($cacheOpensKey)) {
            Cache::put($cacheOpensKey, (int) $model->open_count, 1800);
        }

        $newOpens = Cache::increment($cacheOpensKey);
        $model->increment('open_count');
        $model->refresh();

        if ($newOpens > $maxOpens || $model->open_count > $maxOpens) {
            $this->rejectSession($id, $type, 'max_opens_exceeded');
            return [
                'valid'   => false,
                'reason'  => 'max_opens_exceeded',
                'model'   => $model,
                'message' => "Payment link open limit ({$maxOpens}) has been exceeded.",
            ];
        }

        return [
            'valid'      => true,
            'model'      => $model,
            'payload'    => $payload,
            'open_count' => $newOpens,
            'max_opens'  => $maxOpens,
        ];
    }

    /**
     * Mark session as rejected / user_dropped in DB & Cache.
     */
    public function rejectSession(int $id, string $type = 'hub', string $reason = 'user_dropped'): void
    {
        $model = ($type === 'hub')
            ? PaymentRequest::find($id)
            : Payment::find($id);

        if (!$model) {
            return;
        }

        $statusField = ($type === 'hub') ? 'payment_status' : 'status';
        if ($model->{$statusField} === 'pending') {
            $model->update([
                $statusField => 'user_dropped',
                'closed_at'   => now(),
            ]);

            if ($type === 'hub' && method_exists($model, 'fireClientCallback')) {
                $model->fireClientCallback();
            }

            if ($type === 'buyer' && $model->order_id) {
                $order = \App\Models\Order::find($model->order_id);
                if ($order && $order->payment_status === 'pending') {
                    $order->update([
                        'payment_status' => 'failed',
                        'status'         => 'cancelled',
                    ]);
                }
            }
        }

        $cacheKey = "payment_session_{$type}_{$id}";
        Cache::put($cacheKey, [
            'status' => 'rejected',
            'reason' => $reason,
        ], 600);
    }
}

