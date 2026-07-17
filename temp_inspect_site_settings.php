<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

foreach (App\Models\SiteSetting::all() as $setting) {
    echo $setting->setting_key . ' | ' . $setting->setting_type . ' | ' . var_export($setting->setting_value, true) . ' | ' . var_export($setting->value, true) . "\n";
}
