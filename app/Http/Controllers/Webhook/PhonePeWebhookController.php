<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessPaymentHubWebhook;
use App\Models\Order;
use App\Models\Payment;
use App\Services\PhonePeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PhonePeWebhookController extends Controller
{
    protected PhonePeService $phonePeService;

    public function __construct(PhonePeService $phonePeService)
    {
        $this->phonePeService = $phonePeService;
    }

    public function handleWebhook(Request $request)
    {
        $requestBody = $request->getContent();
        $headers = $request->headers->all();

        $username = config('services.phonepe.webhook_user');
        $password = config('services.phonepe.webhook_pass');

        try {
            // Verify and Parse Callback using SDK
            $callbackResponse = $this->phonePeService->client->verifyCallbackResponse(
                $headers,
                json_decode($requestBody, true),
                $username,
                $password
            );

            $type = $callbackResponse->getType();
            $payload = $callbackResponse->getPayload();

            Log::info('PhonePe Webhook received:', ['type' => $type]);

            // Handle Order Callbacks
            if (in_array($type, ['CHECKOUT_ORDER_COMPLETED', 'CHECKOUT_ORDER_FAILED'])) {
                $transactionId = $payload->getOriginalMerchantOrderId();
                $providerReferenceId = $payload->getOrderId();

                if (!$transactionId) {
                    return response()->json(['status' => 'ignored_no_transaction_id']);
                }

                $eventType = ($type === 'CHECKOUT_ORDER_COMPLETED') ? 'PAYMENT_SUCCESS' : 'PAYMENT_FAILED';

                // ── ROUTE: Payment Hub orders (prefix: hub_) ──────────────────────
                if (str_starts_with($transactionId, 'hub_')) {
                    ProcessPaymentHubWebhook::dispatch(
                        $transactionId,
                        $eventType,
                        $providerReferenceId,
                        json_decode($requestBody, true)
                    );
                    Log::info("PhonePe Webhook: Hub order [{$transactionId}] dispatched to queue.");
                    return response()->json(['status' => 'queued']);
                }

                // ── ROUTE: Internal FIINWAY orders (fallback) ──────────────────
                if (str_starts_with($transactionId, 'pp_') || str_starts_with($transactionId, 'cf_')) {
                    $parts = explode('_', $transactionId);
                    if (count($parts) >= 2) {
                        $orderId = $parts[1];
                        $this->processOrder($orderId, $eventType, [], $providerReferenceId);
                    }
                    return response()->json(['status' => 'success']);
                }
                
                Log::warning("PhonePe Webhook: Unrecognised order prefix for [{$transactionId}]");
                return response()->json(['status' => 'ignored_unknown_prefix']);
            }

            // Handle Refund Callbacks
            if (in_array($type, ['PG_REFUND_COMPLETED', 'PG_REFUND_FAILED'])) {
                $merchantRefundId = $payload->getMerchantRefundId();
                Log::info("PhonePe Webhook: Refund {$merchantRefundId} processed with type {$type}");
                // Add Refund specific handling here in the future
                return response()->json(['status' => 'success']);
            }

            Log::info("PhonePe Webhook: Unhandled callback type {$type}");
            return response()->json(['status' => 'ignored_unhandled_type']);

        } catch (\PhonePe\common\exceptions\PhonePeException $e) {
            Log::error('PhonePe SDK Webhook Validation Error: ' . $e->getMessage(), ['status' => $e->getHttpStatusCode()]);
            return response()->json(['error' => 'Invalid signature or request'], 400);
        } catch (\Exception $e) {
            Log::error('PhonePe Webhook error: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    private function processOrder($orderId, $eventType, $data, $providerReferenceId)
    {
        $order = Order::find($orderId);

        if (!$order) {
            Log::warning("PhonePe Webhook: Order {$orderId} not found.");
            return;
        }

        // Prevent processing if already paid
        if ($order->payment_status === 'paid') {
            Log::info("PhonePe Webhook: Order {$orderId} is already paid. Ignoring.");
            return;
        }

        DB::beginTransaction();
        try {
            if ($eventType === 'PAYMENT_SUCCESS') {

                // 1. Update Payment Record
                $payment = Payment::where('order_id', $order->id)->first();
                if ($payment) {
                    $payment->update([
                        'status'             => 'success',
                        'gateway_payment_id' => $providerReferenceId,
                        'paid_at'            => now(),
                    ]);
                }

                // 2. Update Order
                $order->update([
                    'payment_status' => 'paid',
                    'transaction_id' => $providerReferenceId,
                    'paid_at'        => now(),
                    'status'         => 'confirmed',
                ]);

                // 3. Confirm Items & Shipments
                $order->items()->update(['status' => 'confirmed']);
                $order->shipments()->update(['status' => 'confirmed']);

                Log::info("PhonePe Webhook: Successfully processed payment for Order {$orderId}.");

            } elseif ($eventType === 'PAYMENT_FAILED') {
                Log::info("PhonePe Webhook: Payment failed for Order {$orderId}. User can retry.");
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("PhonePe Webhook: Failed to process order {$orderId}. Error: " . $e->getMessage());
        }
    }
}
