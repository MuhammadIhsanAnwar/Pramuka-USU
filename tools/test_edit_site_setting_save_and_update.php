<?php
require dirname(__DIR__) . '/vendor/autoload.php';
$app = require dirname(__DIR__) . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Filament\Admin\Resources\SiteSettingResource\Pages\EditSiteSetting;
use App\Models\SiteSetting;

$page = new class extends EditSiteSetting {
    public function testSave(array $data): array
    {
        return $this->mutateFormDataBeforeSave($data);
    }
};

$record = SiteSetting::find(14);
if (!$record) {
    echo "Record id 14 not found\n";
    exit(1);
}

$input = [
    'setting_key' => 'home_brand_logos',
    'setting_value' => [
        ['path' => 'logo/01KYJYWCE32QHAQW9WSYC82H0W.png'],
    ],
];

$result = $page->testSave($input);
echo "Normalized data:\n";
echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";

$record->update($result);
echo "Saved record setting_value: ";
echo json_encode($record->refresh()->setting_value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n";
