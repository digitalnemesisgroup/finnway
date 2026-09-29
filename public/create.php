<?php
// public/create.php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Models\User;
use App\Models\PaymentClient;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

header('Content-Type: text/html; charset=utf-8');
echo "<div style='font-family: sans-serif; max-width: 600px; margin: 50px auto; padding: 30px; background: #0f172a; color: #fff; border-radius: 16px;'>";
echo "<h2 style='color: #38bdf8;'>FINNWAY 360 Account Setup</h2>";

try {
    // Clear old caches so middleware updates apply immediately
    @unlink(__DIR__ . '/../bootstrap/cache/config.php');
    @unlink(__DIR__ . '/../bootstrap/cache/routes-v7.php');

    // 1. Payment Admin
    $admin = User::where('email', 'admin@fiinway.com')->first();
    if (!$admin) {
        $admin = User::create([
            'name' => 'Payment Admin',
            'email' => 'admin@fiinway.com',
            'phone' => '9999999999',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'is_active' => true,
            'is_payment_admin' => true,
        ]);
        echo "<p style='color: #4ade80;'>✓ Created Admin: admin@fiinway.com / admin123</p>";
    } else {
        $admin->update([
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'is_payment_admin' => true,
            'is_active' => true,
        ]);
        echo "<p style='color: #4ade80;'>✓ Updated Admin Permissions: admin@fiinway.com / admin123</p>";
    }

    // 2. Demo Merchant User
    $merchantUser = User::where('email', 'merchant@demobusiness.com')->first();
    if (!$merchantUser) {
        $merchantUser = User::create([
            'name' => 'Demo Merchant Business',
            'email' => 'merchant@demobusiness.com',
            'phone' => '9876543210',
            'password' => Hash::make('merchant123'),
            'role' => 'user',
            'is_active' => true,
        ]);
        echo "<p style='color: #4ade80;'>✓ Created Merchant User: merchant@demobusiness.com / merchant123</p>";
    } else {
        $merchantUser->update([
            'password' => Hash::make('merchant123'),
            'is_active' => true,
        ]);
        echo "<p style='color: #4ade80;'>✓ Updated Merchant Password: merchant@demobusiness.com / merchant123</p>";
    }

    // 3. Demo Merchant Payment Client Entry
    $merchantClient = PaymentClient::where('business_email', 'merchant@demobusiness.com')->first();
    if (!$merchantClient) {
        PaymentClient::create([
            'user_id' => $merchantUser->id,
            'name' => 'Demo Enterprise Pvt Ltd',
            'legal_name' => 'Demo Enterprise Private Limited',
            'business_email' => 'merchant@demobusiness.com',
            'api_key' => 'fw_live_demo_' . Str::random(16),
            'api_salt' => Str::random(32),
            'is_active' => true,
            'approval_status' => 'approved',
            'business_type' => 'Private Limited',
            'category' => 'E-Commerce & Retail',
            'pan_number' => 'ABCDE1234F',
            'gstin' => '27ABCDE1234F1Z5',
            'gateway_charge_percent' => 2.00,
            'gst_on_charge_percent' => 18.00,
            'starts_at' => now(),
            'expires_at' => now()->addYears(5),
        ]);
        echo "<p style='color: #4ade80;'>✓ Created Merchant Gateway Entity (Demo Enterprise Pvt Ltd)</p>";
    } else {
        $merchantClient->update([
            'user_id' => $merchantUser->id,
            'is_active' => true,
            'approval_status' => 'approved',
        ]);
        echo "<p style='color: #4ade80;'>✓ Updated Merchant Gateway Entity Status (Approved & Active)</p>";
    }

    // Re-optimize caches cleanly
    try {
        \Illuminate\Support\Facades\Artisan::call('optimize:clear');
        \Illuminate\Support\Facades\Artisan::call('config:cache');
        \Illuminate\Support\Facades\Artisan::call('route:cache');
    } catch (\Throwable $e) {}

    echo "<hr style='border-color: #334155;'>";
    echo "<h3 style='color: #facc15;'>Done! Credentials & Access Permissions Are Active:</h3>";
    echo "<p><strong>Payment Admin:</strong> admin@fiinway.com / admin123</p>";
    echo "<p><strong>Demo Merchant:</strong> merchant@demobusiness.com / merchant123</p>";
    echo "<br><a href='/admin/payment-clients' style='background: #2563eb; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 8px; font-weight: bold;'>Go to Admin Panel &rarr;</a>";

} catch (\Throwable $e) {
    echo "<p style='color: #f87171;'>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}
echo "</div>";
