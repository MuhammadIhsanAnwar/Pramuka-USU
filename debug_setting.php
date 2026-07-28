<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\SiteSetting;

$setting = SiteSetting::find(14);

var_dump(['id' => $setting->id, 'key' => $setting->setting_key, 'type' => $setting->setting_type]);
var_dump($setting->getAttributes()['setting_value']);
var_dump($setting->setting_value);
var_dump(gettype($setting->setting_value));
