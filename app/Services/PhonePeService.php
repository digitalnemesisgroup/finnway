<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use PhonePe\payments\v2\standardCheckout\StandardCheckoutClient;
use PhonePe\payments\v2\models\request\builders\StandardCheckoutPayRequestBuilder;
use PhonePe\payments\v2\models\request\builders\StandardCheckoutRefundRequestBuilder;
use PhonePe\Env;

class PhonePeService
{
    protected string $merchantId;
    protected string $saltKey;
    protected string $saltIndex;
    protected string $env;
    protected string $baseUrl;
    
    // V2 SDK Client
    public StandardCheckoutClient $client;

    public function __construct()
    {
        // Old V1 manual keys (kept for backwards compatibility if needed temporarily)
        $this->merchantId = config('services.phonepe.merchant_id');
        $this->saltKey = config('services.phonepe.salt_key', '');
        $this->saltIndex = config('services.phonepe.salt_index', '1');
        
        $envConfig = config('services.phonepe.env', 'UAT');
        $this->env = $envConfig;

        $this->baseUrl = $this->env === 'PRODUCTION'
            ? 'https://api.phonepe.com/apis/hermes'
            : 'https://api-preprod.phonepe.com/apis/pg-sandbox';
            
        // V2 SDK Initialization
        $clientId = config('services.phonepe.client_id');
        $clientVersion = (int) config('services.phonepe.client_version');
        $clientSecret = config('services.phonepe.client_secret');
        
        // As per docs, SDK says it only supports Env::PRODUCTION, 
        // but the SDK source code actually has Env::UAT defined and working for the sandbox!
        $sdkEnv = ($this->env === 'UAT') ? Env::UAT : Env::PRODUCTION; 

        $this->client = StandardCheckoutClient::getInstance(
            $clientId,
            $clientVersion,
            $clientSecret,
            $sdkEnv
        );
    }

    /**
     * Create an order (initiate payment)
     */
    public function createOrder(array $data): array
    {
        $amountInPaise = round((float) $data['amount'] * 100);

        try {
            $payRequest = StandardCheckoutPayRequestBuilder::builder()
                ->merchantOrderId($data['transaction_id'])
                ->amount($amountInPaise)
                ->redirectUrl($data['redirect_url'])
                ->message("Payment for order " . $data['transaction_id'])
                ->build();

            $payResponse = $this->client->pay($payRequest);

            if ($payResponse->getState() === "PENDING" || $payResponse->getState() === "INITIATED") {
                return [
                    'success' => true,
                    'redirect_url' => $payResponse->getRedirectUrl(),
                    'transaction_id' => $data['transaction_id'],
                ];
            }

            Log::error("PhonePe order creation failed. State: " . $payResponse->getState());
            throw new \Exception("PhonePe payment initiation failed.");
        } catch (\Exception $e) {
            Log::error("PhonePe SDK Exception in createOrder: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Verify Payment Status
     */
    public function verifyPayment(string $transactionId): array
    {
        try {
            $statusCheckResponse = $this->client->getOrderStatus($transactionId, true);
            $state = $statusCheckResponse->getState();
            $status = 'Pending';

            if ($state === 'COMPLETED' || $state === 'SUCCESS' || $state === 'PAYMENT_SUCCESS') {
                $status = 'Success';
            } elseif ($state === 'FAILED' || $state === 'DECLINED') {
                $status = 'Failure';
            }

            $paymentDetails = $statusCheckResponse->getPaymentDetails();
            $latestPayment = !empty($paymentDetails) ? $paymentDetails[0] : null;

            return [
                'status' => $status,
                'payment_id' => $latestPayment ? $latestPayment->getTransactionId() : $transactionId,
                'provider_reference_id' => null, // Not directly exposed at top level in V2 docs, keeping null
            ];
        } catch (\Exception $e) {
            Log::error("PhonePe payment verification failed for transaction {$transactionId}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Initiate a Refund
     */
    public function initiateRefund(string $originalMerchantOrderId, string $merchantRefundId, float $amount): array
    {
        $amountInPaise = round($amount * 100);

        try {
            $refundRequest = StandardCheckoutRefundRequestBuilder::builder()
                ->merchantRefundId($merchantRefundId)
                ->originalMerchantOrderId($originalMerchantOrderId)
                ->amount($amountInPaise)
                ->build();

            $refundResponse = $this->client->refund($refundRequest);

            return [
                'success' => true,
                'refund_id' => $refundResponse->getRefundId(),
                'state' => $refundResponse->getState(),
                'amount' => $refundResponse->getAmount() / 100, // Convert back to rupees
            ];
        } catch (\Exception $e) {
            Log::error("PhonePe Refund initiation failed for order {$originalMerchantOrderId}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get Refund Status
     */
    public function getRefundStatus(string $merchantRefundId): array
    {
        try {
            $statusCheckResponse = $this->client->getRefundStatus($merchantRefundId);

            return [
                'success' => true,
                'original_order_id' => $statusCheckResponse->getOriginalMerchantOrderId(),
                'merchant_refund_id' => $statusCheckResponse->getMerchantRefundId(),
                'state' => $statusCheckResponse->getState(),
                'amount' => $statusCheckResponse->getAmount() / 100,
            ];
        } catch (\Exception $e) {
            Log::error("PhonePe getRefundStatus failed for refund {$merchantRefundId}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Validate Webhook Signature
     */
    public function validateWebhookSignature(string $base64Response, string $xVerifyHeader): bool
    {
        $calculatedSignature = hash('sha256', $base64Response . $this->saltKey) . '###' . $this->saltIndex;
        return hash_equals($calculatedSignature, $xVerifyHeader);
    }
}
