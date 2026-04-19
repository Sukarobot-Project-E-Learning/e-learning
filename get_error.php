<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
try {
    Illuminate\Support\Facades\Artisan::call('migrate');
} catch (\Exception $e) {
    file_put_contents(__DIR__ . '/last_migrate_error.txt', $e->getMessage() . "\n" . $e->getTraceAsString());
}
