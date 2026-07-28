<?php

require dirname(__DIR__) . '/vendor/autoload.php';

$app = require dirname(__DIR__) . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\SiteSetting;

$keys = ['home_brand_logos', 'home_background_image', 'intro_video'];

foreach ($keys as $key) {
    $value = SiteSetting::where('setting_key', $key)->value('setting_value');
    echo $key . ': ' . json_encode($value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . PHP_EOL;
}
