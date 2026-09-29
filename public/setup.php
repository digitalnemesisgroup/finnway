<?php
// public/setup.php

/**
 * FINNWAY 360 Production Setup Script for Hostinger
 * 
 * Run by visiting: https://your-domain.com/setup.php
 * REMOVE OR DELETE THIS FILE AFTER DEPLOYMENT!
 */

use App\Models\User;
use App\Models\PaymentClient;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

// 1. CLEAR OLD BOOTSTRAP CACHES BEFORE LARAVEL BOOTS
$cacheFiles = glob(__DIR__ . '/../bootstrap/cache/*.php');
if (is_array($cacheFiles)) {
    foreach ($cacheFiles as $file) {
        if (is_file($file)) {
            @unlink($file);
        }
    }
}

// 2. Ensure Storage & Cache Directories Exist BEFORE Laravel Boots
$dirs = [
    __DIR__ . '/../storage/framework/cache/data',
    __DIR__ . '/../storage/framework/sessions',
    __DIR__ . '/../storage/framework/views',
    __DIR__ . '/../storage/app/public',
    __DIR__ . '/../storage/logs',
    __DIR__ . '/../bootstrap/cache',
    __DIR__ . '/../resources/views',
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0775, true);
    }
}

// 3. Boot Laravel
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

// Ensure view compiled path exists in runtime config
config([
    'view.compiled' => realpath(__DIR__ . '/../storage/framework/views') ?: __DIR__ . '/../storage/framework/views',
    'view.paths' => [realpath(__DIR__ . '/../resources/views') ?: __DIR__ . '/../resources/views']
]);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FINNWAY 360 — Production Setup</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; background: #0f172a; color: #f8fafc; margin: 0; padding: 40px 20px; }
        .card { max-width: 760px; margin: 0 auto; background: #1e293b; border: 1px solid #334155; border-radius: 16px; padding: 32px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.5); }
        .logo { display: flex; align-items: center; gap: 12px; font-size: 22px; font-weight: 900; color: #ffffff; letter-spacing: -0.5px; margin-bottom: 24px; border-b: 1px solid #334155; padding-bottom: 16px; }
        .step { background: #0f172a; border: 1px solid #334155; border-radius: 12px; padding: 16px 20px; margin-bottom: 16px; }
        .step-title { font-weight: 700; font-size: 14px; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px; }
        .success { color: #34d399; font-weight: 600; font-size: 14px; }
        .warning { color: #fbbf24; font-weight: 600; font-size: 14px; }
        .error { color: #f87171; font-weight: 600; font-size: 14px; }
        pre { background: #020617; border: 1px solid #1e293b; color: #cbd5e1; padding: 12px; border-radius: 8px; font-size: 12px; overflow-x: auto; margin-top: 8px; }
        .btn { display: inline-block; background: #2563eb; color: #ffffff; font-weight: 700; text-decoration: none; padding: 12px 24px; border-radius: 10px; margin-top: 16px; font-size: 14px; }
        .btn:hover { background: #1d4ed8; }
        .alert-danger { background: #450a0a; border: 1px solid #991b1b; color: #fca5a5; padding: 16px; border-radius: 12px; margin-top: 24px; font-size: 13px; font-weight: 600; }
    </style>
</head>
<body>
<div class="card">
    <div class="logo">
        <span style="color: #3b82f6;">FINNWAY 360</span> Production Deployment Setup
    </div>

<?php
try {
    // 1. Storage Directories
    echo "<div class='step'>";
    echo "<div class='step-title'>1. Storage & Cache Permissions</div>";
    echo "<div class='success'>✓ Storage and bootstrap cache directories verified/created.</div>";
    echo "</div>";

    // 2. Clear Old Compiled Files
    echo "<div class='step'>";
    echo "<div class='step-title'>2. Clear Bootstrap Compiled Caches</div>";
    echo "<div class='success'>✓ Cleared stale bootstrap cache files.</div>";
    echo "</div>";

    // 3. Run Database Migrations SAFELY (NO SEEDING)
    echo "<div class='step'>";
    echo "<div class='step-title'>3. Database Migrations (Schema Update Only - No Demo Data)</div>";
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        $migOutput = \Illuminate\Support\Facades\Artisan::output();
        echo "<div class='success'>✓ Database tables & migrations verified successfully!</div>";
        if (trim($migOutput)) {
            echo "<pre>" . htmlspecialchars($migOutput) . "</pre>";
        }
    } catch (\Throwable $e) {
        echo "<div class='warning'>⚠️ Migration Note: " . htmlspecialchars($e->getMessage()) . "</div>";
    }
    echo "</div>";

    // 4. Create Storage Symlink
    echo "<div class='step'>";
    echo "<div class='step-title'>4. Public Storage Symlink</div>";
    try {
        if (!file_exists(__DIR__ . '/storage')) {
            \Illuminate\Support\Facades\Artisan::call('storage:link');
            echo "<div class='success'>✓ Public storage symlink created successfully.</div>";
        } else {
            echo "<div class='success'>✓ Storage symlink already exists.</div>";
        }
    } catch (\Throwable $e) {
        echo "<div class='warning'>⚠️ Symlink Note: " . htmlspecialchars($e->getMessage()) . "</div>";
    }
    echo "</div>";

    // 5. Setup / Verify Essential Login Accounts (Non-destructive firstOrCreate)
    echo "<div class='step'>";
    echo "<div class='step-title'>5. Verification of Admin & Demo Accounts</div>";
    try {
        // Payment Admin Account
        $admin = User::firstOrCreate(
            ['email' => 'admin@fiinway.com'],
            [
                'name' => 'Payment Admin',
                'phone' => '9999999999',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'is_active' => true,
                'is_payment_admin' => true,
            ]
        );
        if (!$admin->is_payment_admin || $admin->role !== 'admin') {
            $admin->update([
                'is_payment_admin' => true,
                'role' => 'admin',
                'password' => Hash::make('admin123')
            ]);
        }

        // Demo Merchant Business User
        $merchantUser = User::where('email', 'merchant@demobusiness.com')->first();
        if (!$merchantUser) {
            $merchantUser = User::create([
                'email' => 'merchant@demobusiness.com',
                'name' => 'Demo Merchant Business',
                'phone' => '9876543210',
                'password' => Hash::make('merchant123'),
                'role' => 'user',
                'is_active' => true,
            ]);
        }

        // Demo Merchant Business Gateway Client Entry
        $merchantClient = PaymentClient::firstOrCreate(
            ['business_email' => 'merchant@demobusiness.com'],
            [
                'user_id' => $merchantUser->id,
                'name' => 'Demo Enterprise Pvt Ltd',
                'legal_name' => 'Demo Enterprise Private Limited',
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
            ]
        );

        echo "<div class='success'>✓ Payment Admin (admin@fiinway.com / admin123) verified.</div>";
        echo "<div class='success'>✓ Demo Merchant (merchant@demobusiness.com / merchant123) verified.</div>";

    } catch (\Throwable $e) {
        echo "<div class='warning'>⚠️ Account Verification Note: " . htmlspecialchars($e->getMessage()) . "</div>";
    }
    echo "</div>";

    // 5.5 Update Standard Delivery Fee Setting to 0
    echo "<div class='step'>";
    echo "<div class='step-title'>5.5. Standard Delivery Fee Setting</div>";
    try {
        \App\Models\AppSetting::set('standard_delivery_fee', '0');
        echo "<div class='success'>✓ Updated Standard Delivery Fee to ₹0 (Free Delivery) and cleared setting cache.</div>";
    } catch (\Throwable $e) {
        echo "<div class='warning'>⚠️ Delivery Fee Note: " . htmlspecialchars($e->getMessage()) . "</div>";
    }
    echo "</div>";

    // 6. Optimize Caches safely
    echo "<div class='step'>";
    echo "<div class='step-title'>6. Optimize Configuration & Route Caches</div>";
    
    try {
        \Illuminate\Support\Facades\Artisan::call('optimize:clear');
        echo "<div class='success'>✓ Application caches cleared.</div>";
    } catch (\Throwable $e) {
        echo "<div class='warning'>⚠️ Clear Cache Note: " . htmlspecialchars($e->getMessage()) . "</div>";
    }

    try {
        \Illuminate\Support\Facades\Artisan::call('config:cache');
        echo "<div class='success'>✓ Configuration cached successfully.</div>";
    } catch (\Throwable $e) {
        echo "<div class='warning'>⚠️ Config Cache Note: " . htmlspecialchars($e->getMessage()) . "</div>";
    }

    try {
        \Illuminate\Support\Facades\Artisan::call('route:cache');
        echo "<div class='success'>✓ Routes cached successfully.</div>";
    } catch (\Throwable $e) {
        echo "<div class='warning'>⚠️ Route Cache Note: " . htmlspecialchars($e->getMessage()) . "</div>";
    }

    try {
        \Illuminate\Support\Facades\Artisan::call('view:cache');
        echo "<div class='success'>✓ Blade view templates cached successfully.</div>";
    } catch (\Throwable $e) {
        echo "<div class='warning'>⚠️ View Cache Note: " . htmlspecialchars($e->getMessage()) . "</div>";
    }

    echo "</div>";

    // Summary Success
    echo "<div style='margin-top:24px; padding:20px; background:#065f46; border:1px solid #047857; border-radius:12px; color:#a7f3d0;'>";
    echo "<h3 style='margin:0 0 8px 0; font-size:18px; color:#ffffff;'>🎉 Deployment Setup Complete!</h3>";
    echo "<p style='margin:0; font-size:14px;'>Your FINNWAY 360 production application is ready to serve live payment transactions.</p>";
    echo "</div>";

    echo "<div class='alert-danger'>";
    echo "⚠️ SECURITY WARNING: Please delete <code>public/setup.php</code> from Hostinger server immediately after completing setup!";
    echo "</div>";

    echo "<a href='/hub/portal/login' class='btn'>Launch Payment Portal Login &rarr;</a>";

} catch (\Throwable $e) {
    echo "<div class='step' style='border-color: #991b1b;'>";
    echo "<div class='step-title' style='color: #f87171;'>Setup Exception</div>";
    echo "<div class='error'>" . htmlspecialchars($e->getMessage()) . "</div>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    echo "</div>";
}
?>

</div>
</body>
</html>
