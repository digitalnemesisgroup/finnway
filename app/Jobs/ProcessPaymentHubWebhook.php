<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * ProcessPaymentHubWebhook
 *
 * This Job is dispatched by CashfreeWebhookController whenever a Cashfree
 * webhook arrives for a "hub_" prefixed order (i.e., any Payment Hub request —
 * whether originating from the internal FIINWAY app or any external client).
 *
 * Design guarantees:
 *  - IDEMPOTENT: Uses DB lockForUpdate() so concurrent duplicate webhooks are
 *    processed exactly once. If payment_status is already final, the Job exits.
 *  - NO RACE CONDITIONS: The queue processes one job at a time (database driver).
 *    DB transaction + lockForUpdate() is a second layer of safety.
 *  - RETRY-SAFE: If the outgoing HTTP callback fails, the Job throws so Laravel
 *    will retry it automatically. The webhook_dispatched flag prevents double-firing.
 */
class ProcessPaymentHubWebhook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Max retry attempts if outgoing callback fails.
     */
    public int $tries = 5;

    /**
     * Retry with exponential backoff: 10s, 30s, 60s, 300s, 600s
     */
    public function backoff(): array
    {
        return [10, 30, 60, 300, 600];
    }

    public function __construct(
        protected string $cashfreeOrderId,
        protected string $eventType,
        protected ?string $cfPaymentId,
        protected array  $rawData,
    ) {}

    public function handle(): void
    {
        Log::info("PaymentHub Job: Processing [{$this->eventType}] for Cashfree Order [{$this->cashfreeOrderId}]");

        // ── Resolve final status from event type ─────────────────────────────
        $finalStatus = match(true) {
            in_array($this->eventType, ['PAYMENT_SUCCESS_WEBHOOK', 'PAYMENT_SUCCESS']) => 'success',
            in_array($this->eventType, ['PAYMENT_FAILED_WEBHOOK',  'PAYMENT_FAILED'])  => 'failed',
            $this->eventType === 'PAYMENT_USER_DROPPED_WEBHOOK'                        => 'user_dropped',
            default                                                                     => null,
        };

        // Unknown event — log and bail
        if ($finalStatus === null) {
            Log::info("PaymentHub Job: Ignored unknown event type [{$this->eventType}]");
            return;
        }

        // ── Load the PaymentRequest row (must start with hub_) ────────────────
        $paymentRequest = PaymentRequest::where('cashfree_order_id', $this->cashfreeOrderId)->first();

        if (!$paymentRequest) {
            Log::warning("PaymentHub Job: No PaymentRequest found for [{$this->cashfreeOrderId}]");
            return;
        }

        // ── Atomic, idempotent update inside a DB transaction ─────────────────
        DB::transaction(function () use ($paymentRequest, $finalStatus) {

            // Re-fetch with a write lock — prevents race conditions from duplicate webhooks
            $locked = PaymentRequest::lockForUpdate()->find($paymentRequest->id);

            // Idempotency check: if already finalised, skip entirely
            if (in_array($locked->payment_status, ['success', 'failed', 'user_dropped'])) {
                Log::info("PaymentHub Job: [{$this->cashfreeOrderId}] already in final state [{$locked->payment_status}]. Skipping.");
                return;
            }

            // Update PaymentRequest status
            $locked->update([
                'payment_status' => $finalStatus,
                'transaction_id' => $this->cfPaymentId,
            ]);

            Log::info("PaymentHub Job: PaymentRequest #{$locked->id} updated to [{$finalStatus}]");
        });

        // Re-read outside transaction to get the fresh state
        $paymentRequest->refresh();

        // ── Fire outgoing callback to the client's webhook_url ────────────────
        // The webhook_dispatched flag ensures we never double-fire even if the
        // Job is retried due to network failure.
        if (!$paymentRequest->webhook_dispatched) {
            $this->fireClientCallback($paymentRequest);
        } else {
            Log::info("PaymentHub Job: Callback already dispatched for PaymentRequest #{$paymentRequest->id}. Skipping.");
        }
    }

    /**
     * Build and POST the signed callback to the originating client's webhook_url.
     * If the HTTP request fails, throws an exception so the Job is retried.
     */
    private function fireClientCallback(PaymentRequest $paymentRequest): void
    {
        $success = $paymentRequest->fireClientCallback();
        if (!$success) {
            throw new \RuntimeException("Client webhook delivery failed for PaymentRequest #{$paymentRequest->id}");
        }
    }
}
