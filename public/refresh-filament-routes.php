<?php

declare(strict_types=1);

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Artisan;

const DEPLOYMENT_TOKEN = '657e8757d462a0d4e70b2b6701e0ed7e9b0ff033f915b96269b9cdbbcf1e7297';

$requestedToken = $_GET['token'] ?? '';

if (! is_string($requestedToken) || ! hash_equals(DEPLOYMENT_TOKEN, $requestedToken)) {
    http_response_code(404);
    exit;
}

header('Content-Type: text/html; charset=UTF-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

$basePath = dirname(__DIR__);
$cachePath = $basePath.'/bootstrap/cache';

try {
    foreach (glob($cachePath.'/*.php') ?: [] as $cacheFile) {
        if (! @unlink($cacheFile)) {
            throw new RuntimeException('Cache Laravel lama tidak dapat dihapus.');
        }
    }

    require $basePath.'/vendor/autoload.php';

    $app = require $basePath.'/bootstrap/app.php';
    $app->make(Kernel::class)->bootstrap();

    foreach (['optimize:clear', 'package:discover'] as $command) {
        if (Artisan::call($command) !== 0) {
            throw new RuntimeException("Perintah {$command} gagal dijalankan.");
        }
    }

    Artisan::call('route:list', ['--path' => 'admin']);
    $adminRoutes = Artisan::output();

    Artisan::call('route:list', ['--path' => 'user']);
    $userRoutes = Artisan::output();

    if (! str_contains($adminRoutes, 'filament.admin.pages.dashboard') || ! str_contains($userRoutes, 'filament.user.pages.dashboard')) {
        throw new RuntimeException('Rute panel belum terdaftar. Pastikan bootstrap/providers.php dan kedua Filament Panel Provider telah diunggah.');
    }

    foreach (['config:cache', 'route:cache', 'view:cache'] as $command) {
        if (Artisan::call($command) !== 0) {
            throw new RuntimeException("Perintah {$command} gagal dijalankan.");
        }
    }

    $deleted = @unlink(__FILE__);
} catch (Throwable $exception) {
    $message = $exception->getMessage();
    $deleted = false;
}
?><!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pemulihan Rute Filament</title>
</head>
<body>
    <h1><?= isset($message) ? 'Pemulihan gagal' : 'Pemulihan berhasil' ?></h1>
    <?php if (isset($message)): ?>
        <p><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>
        <p>Jangan hapus skrip ini sampai seluruh berkas deployment terbaru diunggah, lalu jalankan kembali URL yang sama.</p>
    <?php else: ?>
        <p>Rute <code>/admin</code> dan <code>/user</code> telah dibangun ulang.</p>
        <p><?= $deleted ? 'Skrip pemulihan telah menghapus dirinya sendiri.' : 'Hapus public/refresh-filament-routes.php melalui File Manager sekarang.' ?></p>
    <?php endif; ?>
</body>
</html>
