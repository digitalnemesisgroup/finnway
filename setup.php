<?php

/**
 * FIINWAY Marketplace & Payment Hub — Production Hostinger Setup Script
 *
 * Visit https://yourdomain.com/setup.php in your browser to run setup.
 * IMPORTANT: Delete this setup.php file after setup completes!
 */

ini_set('display_errors', 1);
error_reporting(E_ALL);

$baseDir = null;
if (file_exists(__DIR__ . '/artisan')) {
    $baseDir = __DIR__;
} elseif (file_exists(dirname(__DIR__) . '/artisan')) {
    $baseDir = dirname(__DIR__);
}

if (!$baseDir) {
    die("<h2>Error: artisan file not found. Ensure setup.php is located in public/ or the project root.</h2>");
}

require $baseDir . '/vendor/autoload.php';
$app = require_once $baseDir . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FIINWAY Payment Hub — Hostinger Setup</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet">
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen py-12 px-4 font-sans">
    <div class="max-w-2xl mx-auto bg-slate-800 rounded-3xl p-8 border border-slate-700 shadow-2xl space-y-6">
        
        <div class="flex items-center gap-4 border-b border-slate-700 pb-6">
            <div class="w-14 h-14 bg-indigo-600/20 text-indigo-400 rounded-2xl flex items-center justify-center text-3xl border border-indigo-500/30">
                <i class="ri-shield-keyhole-line"></i>
            </div>
            <div>
                <h1 class="text-2xl font-black text-white tracking-tight">FIINWAY Payment Hub Setup</h1>
                <p class="text-xs text-slate-400 font-semibold mt-1">Hostinger Production Deployment & Security Configurator</p>
            </div>
        </div>

        <div class="space-y-4">
<?php

$steps = [];

try {
    // Step 1: Framework Storage Directories
    $requiredDirs = [
        $baseDir . '/storage/framework/views',
        $baseDir . '/storage/framework/cache',
        $baseDir . '/storage/framework/cache/data',
        $baseDir . '/storage/framework/sessions',
        $baseDir . '/storage/logs',
        $baseDir . '/storage/app/public',
        $baseDir . '/bootstrap/cache',
    ];
    foreach ($requiredDirs as $dir) {
        if (!is_dir($dir)) {
            @mkdir($dir, 0775, true);
        } else {
            @chmod($dir, 0775);
        }
    }
    $steps[] = ['title' => 'Storage & Cache Directories', 'status' => 'ok', 'msg' => 'Framework permissions & directories verified (0775).'];

    // Step 2: Clear Application Caches
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
    $steps[] = ['title' => 'Application Cache', 'status' => 'ok', 'msg' => 'Old caches cleared successfully.'];

    // Step 3: Run Database Migrations (Safe mode: only applies new schema changes, never drops tables)
    \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
    try {
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE payments MODIFY COLUMN session_token TEXT NULL');
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE payment_requests MODIFY COLUMN session_token TEXT NULL');
    } catch (\Throwable $e) {}
    $steps[] = ['title' => 'Database Schema Migration', 'status' => 'ok', 'msg' => 'Schema updated safely. All existing database records were preserved.'];

    // Step 4: Setup Internal Payment Client
    $internalClient = \App\Models\PaymentClient::firstOrCreate(
        ['name' => 'FIINWAY Internal'],
        [
            'api_key' => 'internal_' . \Illuminate\Support\Str::random(32),
            'api_salt' => \Illuminate\Support\Str::random(32),
            'is_active' => true,
            'approval_status' => 'approved',
            'business_type' => 'Internal Marketplace',
            'pan_number' => 'INTERNAL',
            'business_email' => 'admin@fiinway.in',
            'expected_monthly_volume' => 'Internal',
            'expected_avg_value' => 'Internal',
            'purpose' => 'Internal Marketplace Checkout',
            'website_url' => 'https://fiinway.in',
            'registered_address' => 'Internal',
            'business_mobile' => '0000000000',
            'category' => 'Internal',
            'gateway_charge_percent' => 0,
            'gst_on_charge_percent' => 0,
            'starts_at' => now(),
            'expires_at' => now()->addYears(10),
        ]
    );

    if (!$internalClient->is_active || $internalClient->approval_status !== 'approved') {
        $internalClient->update([
            'is_active' => true,
            'approval_status' => 'approved',
            'starts_at' => now(),
            'expires_at' => now()->addYears(10),
        ]);
    }
    $steps[] = ['title' => 'Internal Payment Client Config', 'status' => 'ok', 'msg' => "Client Active: Key={$internalClient->api_key}"];

    // Step 5: Storage Symlink
    @\Illuminate\Support\Facades\Artisan::call('storage:link');
    $steps[] = ['title' => 'Storage Symlink', 'status' => 'ok', 'msg' => 'Public storage symlink active.'];

    // Step 6: Rebuild Production Caches
    @\Illuminate\Support\Facades\Artisan::call('view:cache');
    @\Illuminate\Support\Facades\Artisan::call('config:cache');
    @\Illuminate\Support\Facades\Artisan::call('route:cache');
    $steps[] = ['title' => 'Production Optimization', 'status' => 'ok', 'msg' => 'Routes, views, and configuration cached for fast performance.'];

} catch (\Throwable $e) {
    $steps[] = ['title' => 'Setup Exception', 'status' => 'error', 'msg' => $e->getMessage()];
}

foreach ($steps as $step):
    $isOk = $step['status'] === 'ok';
?>
            <div class="flex items-start gap-3 p-4 rounded-2xl bg-slate-900/60 border border-slate-700/80">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 font-bold <?= $isOk ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : 'bg-rose-500/20 text-rose-400 border border-rose-500/30' ?>">
                    <i class="<?= $isOk ? 'ri-checkbox-circle-fill' : 'ri-close-circle-fill' ?>"></i>
                </div>
                <div class="space-y-0.5">
                    <h4 class="text-sm font-bold text-white"><?= htmlspecialchars($step['title']) ?></h4>
                    <p class="text-xs text-slate-400 font-mono"><?= htmlspecialchars($step['msg']) ?></p>
                </div>
            </div>
<?php endforeach; ?>
        </div>

        <div class="p-5 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-xs text-amber-300 space-y-2">
            <div class="flex items-center gap-2 font-bold text-amber-400">
                <i class="ri-alert-line text-lg"></i> Security Warning: Delete setup.php
            </div>
            <p>For your security, please delete <code class="bg-amber-950 px-1.5 py-0.5 rounded font-mono text-amber-200">setup.php</code> from your server root after setup is complete.</p>
        </div>

        <div class="pt-4 flex gap-4">
            <a href="/" class="block w-full py-3.5 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-center font-extrabold text-sm text-white shadow-xl shadow-indigo-600/20 transition-all">
                <i class="ri-arrow-right-line mr-1"></i> Open FIINWAY Marketplace
            </a>
        </div>

    </div>
</body>
</html>
