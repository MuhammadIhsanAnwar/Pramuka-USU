<?php

namespace Tests\Unit;

use App\Filament\Admin\Resources\SiteSettingResource;
use App\Filament\Admin\Resources\SiteSettingResource\Pages\EditSiteSetting;
use Tests\TestCase;

class SiteSettingResourceTest extends TestCase
{
    public function test_mutate_form_data_before_fill_normalizes_boolean_home_brand_logo_state(): void
    {
        $page = new class extends EditSiteSetting {
            public function mutateFormDataBeforeFillForTest(array $data): array
            {
                return $this->mutateFormDataBeforeFill($data);
            }
        };

        $result = $page->mutateFormDataBeforeFillForTest([
            'setting_key' => 'home_brand_logos',
            'setting_value' => true,
        ]);

        $this->assertSame([], $result['setting_value']);
    }

    public function test_mutate_form_data_before_save_normalizes_boolean_home_brand_logo_state(): void
    {
        $page = new class extends EditSiteSetting {
            public function mutateFormDataBeforeSaveForTest(array $data): array
            {
                return $this->mutateFormDataBeforeSave($data);
            }
        };

        $result = $page->mutateFormDataBeforeSaveForTest([
            'setting_key' => 'home_brand_logos',
            'setting_value' => true,
        ]);

        $this->assertSame([], $result['setting_value']);
    }

    public function test_normalize_home_brand_logos_for_form_converts_string_values_to_array(): void
    {
        $this->assertSame([
            ['path' => '/storage/logo.png'],
        ], SiteSettingResource::normalizeHomeBrandLogosForForm(['/storage/logo.png']));
    }
}
