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
        $settingKey = $data['setting_key'] ?? $this->record?->setting_key;

        if ($settingKey === 'home_brand_logos') {
            $value = $data['setting_value'] ?? $data['home_brand_logos'] ?? [];

            if (is_string($value)) {
                $decoded = json_decode($value, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $value = $decoded;
                }
            }

            $normalizedValue = \App\Filament\Admin\Resources\SiteSettingResource::normalizeHomeBrandLogosForForm($value);
            $data['setting_value'] = is_array($normalizedValue) ? $normalizedValue : [];
        }

        if (in_array($settingKey, ['home_background_image', 'intro_video'], true)) {
            $value = $data['setting_value'] ?? [];

            if (is_array($value)) {
                $value = $value[0] ?? null;
            }

            if (is_string($value)) {
                $decoded = json_decode($value, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $value = $decoded;
                }
            }

            if (is_array($value)) {
                $value = $value[0] ?? null;
            }

            if (filled($value) && ! is_string($value)) {
                $value = (string) $value;
            }

            $data['setting_value'] = filled($value) ? $value : null;
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $settingKey = $data['setting_key'] ?? $this->record?->setting_key;

        if ($settingKey === 'home_brand_logos') {
            $normalizedValue = $data['setting_value'] ?? $data['home_brand_logos'] ?? [];

            if (is_string($normalizedValue)) {
                $decoded = json_decode($normalizedValue, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $normalizedValue = $decoded;
                }
            }

            if (! is_array($normalizedValue)) {
                $normalizedValue = [];
            }

            $data['setting_value'] = collect($normalizedValue)
                ->values()
                ->map(fn ($item) => \App\Filament\Admin\Resources\SiteSettingResource::normalizeStoredLogoPath($item))
                ->filter(fn ($value): bool => filled($value))
                ->values()
                ->all();
        }

        if (in_array($settingKey, ['home_background_image', 'intro_video'], true)) {
            $value = $data['setting_value'] ?? [];

            if (is_array($value)) {
                $value = $value[0] ?? null;
            }

            if (is_string($value)) {
                $decoded = json_decode($value, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $value = $decoded;
                }
            }

            if (is_array($value)) {
                $value = $value[0] ?? null;
            }

            if (filled($value) && ! is_string($value)) {
                $value = (string) $value;
            }

            $data['setting_value'] = filled($value) ? $value : null;
        }

        return $data;
    }
}