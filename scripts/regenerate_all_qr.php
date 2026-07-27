<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Services\MemberQrCodeService;

$users = User::all();
if ($users->isEmpty()) {
    echo "NO USERS\n";
    return;
}

foreach ($users as $user) {
    echo "USER: {$user->id} {$user->name} -> ";
    $path = app(MemberQrCodeService::class)->generateFor($user, true);
    echo "{$path}\n";
}
