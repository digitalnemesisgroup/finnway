<?php

namespace Tests\Feature;

use App\Models\AppSetting;
use App\Models\PaymentClient;
use App\Models\PaymentRequest;
use App\Services\PaymentSessionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Tests\TestCase;

class PaymentSessionSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected PaymentClient $client;
    protected PaymentSessionService $sessionService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = PaymentClient::create([
            'name'            => 'Test Merchant',
            'api_key'         => 'key_test_123',
            'api_salt'        => 'salt_test_123',
            'is_active'       => true,
            'approval_status' => 'approved',
            'expires_at'      => now()->addYear(),
        ]);

        $this->sessionService = app(PaymentSessionService::class);
        AppSetting::set('payment_session_max_opens', 2);
        AppSetting::set('payment_session_ttl_minutes', 15);
    }

    public function test_it_creates_encrypted_session_token()
    {
        $pr = PaymentRequest::create([
            'payment_client_id' => $this->client->id,
            'client_order_id'   => 'ORD-101',
            'amount'            => 500.00,
            'currency'          => 'INR',
            'return_url'        => 'https://example.com/return',
            'webhook_url'       => 'https://example.com/webhook',
            'payment_status'    => 'pending',
            'cashfree_session_id' => 'cs_test_123',
        ]);

        $token = $this->sessionService->createSession($pr, 'hub');

        $this->assertNotEmpty($token);
        $this->assertNotNull($pr->fresh()->session_token);
        $this->assertEquals(2, $pr->fresh()->max_opens);

        $decrypted = json_decode(Crypt::decryptString($token), true);
        $this->assertEquals($pr->id, $decrypted['id']);
        $this->assertEquals('hub', $decrypted['type']);
    }

    public function test_open_count_limit_enforcement()
    {
        $pr = PaymentRequest::create([
            'payment_client_id' => $this->client->id,
            'client_order_id'   => 'ORD-102',
            'amount'            => 1000.00,
            'return_url'        => 'https://example.com/return',
            'webhook_url'       => 'https://example.com/webhook',
            'payment_status'    => 'pending',
            'cashfree_session_id' => 'cs_test_456',
        ]);

        $token = $this->sessionService->createSession($pr, 'hub');

        // 1st access -> allowed
        $res1 = $this->get(route('hub.checkout', ['token' => $token]));
        $res1->assertStatus(200);
        $this->assertEquals(1, $pr->fresh()->open_count);

        // 2nd access -> allowed
        $res2 = $this->get(route('hub.checkout', ['token' => $token]));
        $res2->assertStatus(200);
        $this->assertEquals(2, $pr->fresh()->open_count);

        // 3rd access -> rejected (max_opens = 2)
        $res3 = $this->get(route('hub.checkout', ['token' => $token]));
        $res3->assertStatus(403);
        $this->assertEquals('user_dropped', $pr->fresh()->payment_status);
    }

    public function test_abandonment_beacon_marks_status_user_dropped()
    {
        $pr = PaymentRequest::create([
            'payment_client_id' => $this->client->id,
            'client_order_id'   => 'ORD-103',
            'amount'            => 750.00,
            'return_url'        => 'https://example.com/return',
            'webhook_url'       => 'https://example.com/webhook',
            'payment_status'    => 'pending',
            'cashfree_session_id' => 'cs_test_789',
        ]);

        $token = $this->sessionService->createSession($pr, 'hub');

        $response = $this->post(route('hub.checkout.abandon', ['token' => $token]));
        $response->assertStatus(200);

        $this->assertEquals('user_dropped', $pr->fresh()->payment_status);
    }

    public function test_legacy_url_redirects_to_encrypted_session_url()
    {
        $pr = PaymentRequest::create([
            'payment_client_id' => $this->client->id,
            'client_order_id'   => 'ORD-104',
            'amount'            => 300.00,
            'return_url'        => 'https://example.com/return',
            'webhook_url'       => 'https://example.com/webhook',
            'payment_status'    => 'pending',
            'cashfree_session_id' => 'cs_test_legacy',
        ]);

        $response = $this->get(route('hub.checkout.legacy', ['id' => $pr->id]));
        $response->assertStatus(302);
        
        $token = $pr->fresh()->session_token;
        $this->assertNotEmpty($token);
        $response->assertRedirect(route('hub.checkout', ['token' => $token]));
    }

    public function test_webhook_callback_dispatched_with_hmac_signature()
    {
        \Illuminate\Support\Facades\Http::fake([
            'https://example.com/webhook' => \Illuminate\Support\Facades\Http::response(['status' => 'success'], 200),
        ]);

        $pr = PaymentRequest::create([
            'payment_client_id' => $this->client->id,
            'client_order_id'   => 'ORD-105',
            'amount'            => 1200.00,
            'return_url'        => 'https://example.com/return',
            'webhook_url'       => 'https://example.com/webhook',
            'payment_status'    => 'success',
            'transaction_id'    => 'cf_txn_999',
        ]);

        $dispatched = $pr->fireClientCallback();
        $this->assertTrue($dispatched);
        $this->assertTrue($pr->fresh()->webhook_dispatched);

        \Illuminate\Support\Facades\Http::assertSent(function ($request) use ($pr) {
            return $request->url() === 'https://example.com/webhook' &&
                   $request->hasHeader('X-Hub-Signature') &&
                   $request['client_order_id'] === 'ORD-105' &&
                   $request['status'] === 'success';
        });
    }
}
