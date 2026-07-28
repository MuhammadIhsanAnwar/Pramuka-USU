<?php

namespace Tests\Unit;

use App\Filament\Admin\Resources\SiteSettingResource;
use App\Filament\Admin\Resources\SiteSettingResource\Pages\EditSiteSetting;
use App\Models\SiteSetting;
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

    public function test_normalize_home_brand_logos_for_form_converts_home_brand_logo_singular_path(): void
    {
        $this->assertSame([
            ['path' => 'home_brand_logos/logo.png'],
        ], SiteSettingResource::normalizeHomeBrandLogosForForm(['/storage/home_brand_logo/logo.png']));
    }

    public function test_normalize_home_brand_logos_for_form_maintains_logo_directory_path(): void
    {
        $this->assertSame([
            ['path' => 'logo/logo.png'],
        ], SiteSettingResource::normalizeHomeBrandLogosForForm(['/storage/logo/logo.png']));
    }

    public function test_mutate_form_data_before_save_uses_record_setting_key_when_missing_payload_key(): void
    {
        $page = new class extends EditSiteSetting {
            public $record;

            public function setRecord($record): void
            {
                $this->record = $record;
            }

            public function mutateFormDataBeforeSaveForTest(array $data): array
            {
                return $this->mutateFormDataBeforeSave($data);
            }
        };

        $page->setRecord(new SiteSetting(['setting_key' => 'home_brand_logos']));

        $result = $page->mutateFormDataBeforeSaveForTest([
            'setting_value' => [
                ['path' => '/storage/home_brand_logos/logo.png'],
            ],
        ]);

        $this->assertSame(['home_brand_logos/logo.png'], $result['setting_value']);
    }

    public function test_upload_configuration_for_home_background_image_uses_image_rules(): void
    {
        $config = SiteSettingResource::getUploadConfiguration('home_background_image');

        $this->assertSame('beranda', $config['directory']);
        $this->assertSame(5120, $config['maxSize']);
        $this->assertSame(['image/jpeg', 'image/png'], $config['acceptedFileTypes']);
        $this->assertTrue($config['image']);
    }

    public function test_upload_configuration_for_intro_video_uses_video_rules(): void
    {
        $config = SiteSettingResource::getUploadConfiguration('intro_video');

        $this->assertSame('beranda', $config['directory']);
        $this->assertSame(10240, $config['maxSize']);
        $this->assertSame(['video/mp4'], $config['acceptedFileTypes']);
        $this->assertFalse($config['image']);
    }
}
