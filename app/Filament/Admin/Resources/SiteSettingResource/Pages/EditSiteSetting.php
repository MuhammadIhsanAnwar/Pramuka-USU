<?php

namespace App\Filament\Admin\Resources\SiteSettingResource\Pages;

use App\Filament\Admin\Resources\SiteSettingResource;
use Filament\Resources\Pages\EditRecord;

class EditSiteSetting extends EditRecord
{
    protected static string $resource = SiteSettingResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if (($data['setting_key'] ?? null) === 'home_brand_logos') {
            $data['setting_value'] = \App\Filament\Admin\Resources\SiteSettingResource::normalizeHomeBrandLogosForForm($data['setting_value'] ?? []);
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (($data['setting_key'] ?? null) === 'home_brand_logos') {
            $data['setting_value'] = collect($data['setting_value'] ?? [])
                ->map(function ($item) {
                    if (is_array($item)) {
                        return $item['path'] ?? null;
                    }

                    return $item;
                })
                ->filter(fn ($value): bool => filled($value))
                ->values()
                ->all();
        }

        return $data;
    }
}