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

$cases = [
    'array_string_path' => [
        'setting_key' => 'home_brand_logos',
        'setting_value' => ['logo/01KYJYWCE32QHAQW9WSYC82H0W.png'],
    ],
    'array_path_field' => [
        'setting_key' => 'home_brand_logos',
        'setting_value' => [
            ['path' => 'logo/01KYJYWCE32QHAQW9WSYC82H0W.png'],
        ],
    ],
    'array_storage_path_field' => [
        'setting_key' => 'home_brand_logos',
        'setting_value' => [
            ['path' => '/storage/home_brand_logos/new-logo.png'],
        ],
    ],
];

foreach ($cases as $name => $input) {
    echo "CASE: $name\n";
    $result = $page->testSave($input);
    echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL;
    echo str_repeat('-', 40) . PHP_EOL;
}
