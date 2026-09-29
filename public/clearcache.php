<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

try {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
    echo "Cache cleared successfully.<br>";
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    echo "View cache cleared successfully.<br>";
    \Illuminate\Support\Facades\Artisan::call('route:clear');
    echo "Route cache cleared successfully.<br>";
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    echo "Config cache cleared successfully.<br>";
    
    // Manually create the symlink in the exact same folder as this script
    $target = __DIR__ . '/../storage/app/public';
    $link = __DIR__ . '/storage';
    
    // file_exists returns false for broken symlinks, so we check is_link directly!
    if (is_link($link)) {
        unlink($link);
    } elseif (file_exists($link)) {
        // It's a real directory, attempt to rename or remove it
        @rename($link, __DIR__ . '/storage_backup_' . time());
    }
    
    if (@symlink($target, $link)) {
        echo "Storage link created manually at exactly " . $link . "<br>";
    } else {
        echo "Failed to create manual symlink. Hostinger might have disabled the symlink() function.<br>";
    }
    
    echo "<br><b>All caches have been successfully cleared and storage is linked!</b>";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}
