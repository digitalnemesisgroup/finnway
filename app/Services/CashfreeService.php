<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;
use Cashfree\Cashfree;
use Cashfree\Model\CreateOrderRequest;
use Cashfree\Model\CustomerDetails;
use Cashfree\Model\OrderMeta;

class CashfreeService
{
    public function __construct()
    {
        Cashfree::$XClientId = config('services.cashfree.key_id', 'demo');
        Cashfree::$XClientSecret = config('services.cashfree.key_secret', 'demo');
        Cashfree::$XEnvironment = config('services.cashfree.mode', 'sandbox') === 'production' 
                                    ? Cashfree::$PRODUCTION 
                                    : Cashfree::$SANDBOX;
    }

    public function createOrder(Order $order): array
    {
        $x_api_version = "2023-08-01";

        $create_order_request = new CreateOrderRequest();
        $create_order_request->setOrderAmount(round((float)$order->total, 2));
        $create_order_request->setOrderCurrency("INR");
        
        // Ensure idempotency for creating gateway order
        $cashfreeOrderId = 'cf_' . $order->id . '_' . time();
        $create_order_request->setOrderId($cashfreeOrderId);

        $customer_details = new CustomerDetails();
        $customer_details->setCustomerId('usr_' . $order->user_id);
        
        // Ensure phone number exists, fallback to demo if not
        $phone = $order->user->phone ?? '9999999999';
        if (empty($phone)) $phone = '9999999999';
        $customer_details->setCustomerPhone($phone);
        
        $create_order_request->setCustomerDetails($customer_details);

        $order_meta = new OrderMeta();
        $order_meta->setReturnUrl(route('payment.verify', ['order' => $order->id]) . '?order_id={order_id}');
        $create_order_request->setOrderMeta($order_meta);

        $cashfree = new Cashfree();

        try {
            $result = $cashfree->PGCreateOrder($x_api_version, $create_order_request);
            
            return [
                'id' => $result[0]->getOrderId(),
                'payment_session_id' => $result[0]->getPaymentSessionId(),
                'status' => $result[0]->getOrderStatus(),
            ];
        } catch (\Exception $e) {
            Log::error("Cashfree order creation failed for order #{$order->id}: " . $e->getMessage());
            throw $e;
        }
    }

    public function verifyPayment(string $cashfreeOrderId): array
    {
        // Re-initialize for this instance
        Cashfree::$XClientId = config('services.cashfree.key_id', 'demo');
        Cashfree::$XClientSecret = config('services.cashfree.key_secret', 'demo');
        Cashfree::$XEnvironment = config('services.cashfree.mode', 'sandbox') === 'production' 
                                    ? Cashfree::$PRODUCTION 
                                    : Cashfree::$SANDBOX;

        $cashfree = new Cashfree();
        $x_api_version = "2023-08-01";

        try {
            $response = $cashfree->PGOrderFetchPayments($x_api_version, $cashfreeOrderId);
            
            $transactions = $response[0] ?? [];
            $orderStatus = "Failure";
            $paymentId = null;

            if (is_array($transactions) || $transactions instanceof \Traversable) {
                foreach ($transactions as $transaction) {
                    if ($transaction->getPaymentStatus() === "SUCCESS") {
                        $orderStatus = "Success";
                        $paymentId = $transaction->getCfPaymentId();
                        break;
                    } elseif ($transaction->getPaymentStatus() === "PENDING") {
                        $orderStatus = "Pending";
                    }
                }
            }

            return [
                'status' => $orderStatus,
                'payment_id' => $paymentId,
            ];
        } catch (\Exception $e) {
            Log::error("Cashfree payment verification failed for order {$cashfreeOrderId}: " . $e->getMessage());
            throw $e;
        }
    }
}
