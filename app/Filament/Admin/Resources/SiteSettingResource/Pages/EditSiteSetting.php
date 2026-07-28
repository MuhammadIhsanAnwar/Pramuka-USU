<?php

namespace App\Filament\Admin\Resources\SiteSettingResource\Pages;

use App\Filament\Admin\Resources\SiteSettingResource;
use Filament\Resources\Pages\EditRecord;

class EditSiteSetting extends EditRecord
{
    protected static string $resource = SiteSettingResource::class;

    protected static ?string $title = 'Ubah Pengaturan Website';

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if (($data['setting_key'] ?? null) === 'home_brand_logos') {
            $normalizedValue = \App\Filament\Admin\Resources\SiteSettingResource::normalizeHomeBrandLogosForForm($data['setting_value'] ?? []);
            $data['setting_value'] = is_array($normalizedValue) ? $normalizedValue : [];
            $data['home_brand_logos_items'] = is_array($normalizedValue) ? $normalizedValue : [];
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (($data['setting_key'] ?? null) === 'home_brand_logos') {
            $normalizedValue = $data['home_brand_logos_items'] ?? ($data['setting_value'] ?? []);

            if (! is_array($normalizedValue)) {
                $normalizedValue = [];
            }

            $data['setting_value'] = collect($normalizedValue)
                ->values()
                ->map(function ($item) {
                    if (is_array($item)) {
                        return \App\Filament\Admin\Resources\SiteSettingResource::normalizeStoredLogoPath($item);
                    }

                    return \App\Filament\Admin\Resources\SiteSettingResource::normalizeStoredLogoPath($item);
                })
                ->filter(fn ($value): bool => filled($value))
                ->values()
                ->all();

            unset($data['home_brand_logos_items']);
        }

        return $data;
    }
}