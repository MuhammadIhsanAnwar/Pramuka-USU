<?php
require dirname(__DIR__) . '/vendor/autoload.php';
$app = require dirname(__DIR__) . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Filament\Admin\Resources\SiteSettingResource\Pages\EditSiteSetting;
use App\Models\SiteSetting;

$setting = SiteSetting::find(14);
if (! $setting) {
    echo "Record not found\n";
    exit(1);
}

$attributes = $setting->getAttributes();
$toArray = $setting->toArray();
$attrToArray = $setting->attributesToArray();

printf("RAW ATTRIBUTE TYPE: %s\n", gettype($attributes['setting_value'] ?? null));
printf("RAW ATTRIBUTE: %s\n", var_export($attributes['setting_value'] ?? null, true));
printf("ATTRIBUTES TO ARRAY: %s\n", var_export($attrToArray['setting_value'] ?? null, true));
printf("TO ARRAY: %s\n", var_export($toArray['setting_value'] ?? null, true));

$page = new class extends EditSiteSetting {
    public function callMutate(array $data): array
    {
        return $this->mutateFormDataBeforeFill($data);
    }
};

$page->record = $setting;

$data = array_merge($attributes, $toArray);
echo "\n--- merge attributes + toArray ---\n";
var_export($data);
echo "\n\n--- mutate fill ---\n";
var_export($page->callMutate($data));
echo "\n";
