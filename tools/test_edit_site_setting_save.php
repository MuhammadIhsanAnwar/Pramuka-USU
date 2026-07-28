<?php
require dirname(__DIR__) . '/vendor/autoload.php';
$app = require dirname(__DIR__) . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Filament\Admin\Resources\SiteSettingResource\Pages\EditSiteSetting;

$page = new class extends EditSiteSetting {
    public function testSave(array $data): array
    {
        return $this->mutateFormDataBeforeSave($data);
    }
};

$data = [
    'setting_key' => 'home_brand_logos',
    'home_brand_logos_items' => [
        ['path' => '/storage/home_brand_logos/new-logo.png'],
    ],
];

$result = $page->testSave($data);
echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL;
