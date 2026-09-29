<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class InternalWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $signature = $request->header('X-Hub-Signature');
        
        $internalClient = PaymentClient::where('name', 'FIINWAY Internal')->first();
        
        if (!$internalClient) {
            Log::error('Internal Webhook: FIINWAY Internal client not found.');
            return response()->json(['error' => 'Client not found'], 404);
        }

        $payload = $request->getContent();
        $expectedSignature = hash_hmac('sha256', $payload, $internalClient->api_salt);

        if (!hash_equals($expectedSignature, $signature)) {
            Log::warning('Internal Webhook: Invalid signature.');
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        $data = json_decode($payload, true);
        
        if (!isset($data['client_order_id'])) {
            return response()->json(['error' => 'Missing client_order_id'], 400);
        }

        $clientOrderId = $data['client_order_id'];
        $status = $data['status'] ?? 'pending';
        $transactionId = $data['transaction_id'] ?? null;

        if (str_starts_with($clientOrderId, 'FIINWAY_')) {
            $parts = explode('_', $clientOrderId);
            $orderId = $parts[1] ?? null;
            if ($orderId && is_numeric($orderId)) {
                $this->processOrder((int)$orderId, $status, $transactionId);
            }
        }

        return response()->json(['status' => 'success']);
    }

    private function processOrder($orderId, $status, $transactionId)
    {
        $order = Order::find($orderId);

        if (!$order) {
            Log::warning("Internal Webhook: Order {$orderId} not found.");
            return;
        }

        if ($order->payment_status === 'paid') {
            Log::info("Internal Webhook: Order {$orderId} is already paid. Ignoring.");
            return;
        }

        DB::beginTransaction();
        try {
            if ($status === 'success') {
                $payment = Payment::where('order_id', $order->id)->first();
                if ($payment) {
                    $payment->update([
                        'status'             => 'success',
                        'gateway_payment_id' => $transactionId,
                        'paid_at'            => now(),
                    ]);
                }

                $order->update([
                    'payment_status' => 'paid',
                    'transaction_id' => $transactionId,
                    'paid_at'        => now(),
                    'status'         => 'confirmed',
                ]);

                $order->items()->update(['status' => 'confirmed']);
                $order->shipments()->update(['status' => 'confirmed']);

                Log::info("Internal Webhook: Successfully processed payment for Order {$orderId}.");
            } elseif ($status === 'failed') {
                Log::info("Internal Webhook: Payment failed for Order {$orderId}.");
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Internal Webhook: Failed to process order {$orderId}. Error: " . $e->getMessage());
        }
    }
}
