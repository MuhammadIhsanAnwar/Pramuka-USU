<?php

require dirname(__DIR__) . '/vendor/autoload.php';

$app = require dirname(__DIR__) . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\SiteSetting;

$id = 14;
$setting = SiteSetting::find($id);

if ($setting === null) {
    echo "Record id={$id} not found" . PHP_EOL;
    exit(1);
}

echo "id={$setting->id}\n";
echo "setting_key={$setting->setting_key}\n";
echo "setting_type={$setting->setting_type}\n";
echo "setting_value=" . json_encode($setting->setting_value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . PHP_EOL;
