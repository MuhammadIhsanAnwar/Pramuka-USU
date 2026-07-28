<?php
require dirname(__DIR__) . '/vendor/autoload.php';
$app = require dirname(__DIR__) . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Filament\Admin\Resources\SiteSettingResource\Pages\EditSiteSetting;
use App\Models\SiteSetting;

$setting = SiteSetting::find(14);
if (! $setting) {
    echo "Record 14 not found\n";
    exit(1);
}

echo "=== DB record ===\n";
echo 'setting_key: ' . $setting->setting_key . "\n";
echo 'raw attribute type: ' . gettype($setting->getAttributes()['setting_value']) . "\n";
echo 'raw attribute: ' . var_export($setting->getAttributes()['setting_value'], true) . "\n";
echo 'casted setting_value type: ' . gettype($setting->setting_value) . "\n";
echo 'casted setting_value: ' . var_export($setting->setting_value, true) . "\n";
echo 'accessor value type: ' . gettype($setting->value) . "\n";
echo 'accessor value: ' . var_export($setting->value, true) . "\n";

$page = new class extends EditSiteSetting {
    public function callMutateFill(array $data): array
    {
        return $this->mutateFormDataBeforeFill($data);
    }

    public function callMutateSave(array $data): array
    {
        return $this->mutateFormDataBeforeSave($data);
    }
};
$page->record = $setting;

$payloads = [
    ['setting_key' => 'home_brand_logos', 'setting_value' => true],
    ['setting_value' => true],
    ['setting_key' => 'home_brand_logos', 'setting_value' => ['foo']],
    ['setting_value' => ['foo']],
];

foreach ($payloads as $payload) {
    echo "\n--- payload: " . json_encode($payload) . " ---\n";
    $result = $page->callMutateFill($payload);
    echo 'mutateFormDataBeforeFill => ' . var_export($result, true) . "\n";
}
