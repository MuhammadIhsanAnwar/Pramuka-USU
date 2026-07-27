<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$uuid = '6ddd2f42-b730-43ab-b75d-a9e5658a52b0';
$user = User::where('uuid', $uuid)->first();
if ($user) {
    echo "FOUND: {$user->id} {$user->name}\n";
    echo "qr_code_path={$user->qr_code_path}\n";
    echo "qr_code_url=" . asset('storage/'.$user->qr_code_path) . "\n";
} else {
    echo "NOT_FOUND\n";
}
