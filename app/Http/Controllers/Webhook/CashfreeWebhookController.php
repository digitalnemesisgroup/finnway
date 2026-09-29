<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessPaymentHubWebhook;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Cashfree\Cashfree;

class CashfreeWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        // Set Cashfree Credentials
        Cashfree::$XClientId = config('services.cashfree.key_id');
        Cashfree::$XClientSecret = config('services.cashfree.key_secret');
        Cashfree::$XEnvironment = config('services.cashfree.mode', 'sandbox') === 'production'
                                    ? Cashfree::$PRODUCTION
                                    : Cashfree::$SANDBOX;

        $signature = $request->header('x-webhook-signature');
        $timestamp = $request->header('x-webhook-timestamp');
        $payload   = $request->getContent();

        if (!$signature || !$timestamp) {
            Log::warning('Cashfree Webhook missing signature/timestamp headers.');
            return response()->json(['error' => 'Missing headers'], 400);
        }

        try {
            $cashfree    = new Cashfree();
            $cashfree->PGVerifyWebhookSignature($signature, $payload, $timestamp);

            $eventData   = json_decode($payload, true);
            $eventType   = $eventData['type'] ?? 'UNKNOWN';
            $cfPaymentId = $eventData['data']['payment']['cf_payment_id'] ?? null;

            Log::info('Cashfree Webhook received:', ['type' => $eventType]);

            if (!isset($eventData['data']['order']['order_id'])) {
                return response()->json(['status' => 'ignored_no_order_id']);
            }

            $cashfreeOrderId = $eventData['data']['order']['order_id'];

            // ── ROUTE: Payment Hub orders (prefix: hub_) ──────────────────────
            // Dispatch to a Queue Job for atomic, idempotent, race-condition-free
            // processing. Cashfree does NOT know these are from external apps.
            if (str_starts_with($cashfreeOrderId, 'hub_')) {
                ProcessPaymentHubWebhook::dispatch(
                    $cashfreeOrderId,
                    $eventType,
                    $cfPaymentId,
                    $eventData['data'],
                );
                Log::info("Cashfree Webhook: Hub order [{$cashfreeOrderId}] dispatched to queue.");
                return response()->json(['status' => 'queued']);
            }

            // ── ROUTE: Internal FIINWAY orders (prefix: cf_) ──────────────────
            // Original, unmodified internal processing.
            if (str_starts_with($cashfreeOrderId, 'cf_')) {
                $parts = explode('_', $cashfreeOrderId);
                if (count($parts) >= 2) {
                    $orderId = $parts[1];
                    $this->processOrder($orderId, $eventType, $eventData['data']);
                }
                return response()->json(['status' => 'success']);
            }

            Log::warning("Cashfree Webhook: Unrecognised order prefix for [{$cashfreeOrderId}]");
            return response()->json(['status' => 'ignored_unknown_prefix']);

        } catch (\Exception $e) {
            Log::error('Cashfree Webhook signature verification failed: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid signature'], 401);
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // INTERNAL FIINWAY ORDER PROCESSOR — untouched, exactly as before
    // ─────────────────────────────────────────────────────────────────────────
    private function processOrder($orderId, $eventType, $data)
    {
        $order = Order::find($orderId);

        if (!$order) {
            Log::warning("Cashfree Webhook: Order {$orderId} not found.");
            return;
        }

        // Prevent processing if already paid
        if ($order->payment_status === 'paid') {
            Log::info("Cashfree Webhook: Order {$orderId} is already paid. Ignoring.");
            return;
        }

        DB::beginTransaction();
        try {
            if ($eventType === 'PAYMENT_SUCCESS_WEBHOOK' || $eventType === 'PAYMENT_SUCCESS') {

                $paymentId = $data['payment']['cf_payment_id'] ?? null;

                // 1. Update Payment Record
                $payment = Payment::where('order_id', $order->id)->first();
                if ($payment) {
                    $payment->update([
                        'status'             => 'success',
                        'gateway_payment_id' => $paymentId,
                        'paid_at'            => now(),
                    ]);
                }

                // 2. Update Order
                $order->update([
                    'payment_status' => 'paid',
                    'transaction_id' => $paymentId,
                    'paid_at'        => now(),
                    'status'         => 'confirmed',
                ]);

                // 3. Confirm Items & Shipments
                $order->items()->update(['status' => 'confirmed']);
                $order->shipments()->update(['status' => 'confirmed']);

                // Note: Stock was already reserved atomically during placeOrder.

                Log::info("Cashfree Webhook: Successfully processed payment for Order {$orderId}.");

            } elseif ($eventType === 'PAYMENT_FAILED_WEBHOOK' || $eventType === 'PAYMENT_FAILED') {
                Log::info("Cashfree Webhook: Payment failed for Order {$orderId}. User can retry.");
            } elseif ($eventType === 'PAYMENT_USER_DROPPED_WEBHOOK') {
                Log::info("Cashfree Webhook: User dropped/abandoned payment for Order {$orderId}. Order remains pending for retry.");
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Cashfree Webhook: Failed to process order {$orderId}. Error: " . $e->getMessage());
        }
    }
}
