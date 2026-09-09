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

        if ($settingKey === 'footer_social_links') {
            $value = $data['setting_value'] ?? $data['footer_social_links'] ?? [];

            if (is_string($value)) {
                $decoded = json_decode($value, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $value = $decoded;
                }
            }

            $data['footer_social_links'] = is_array($value) ? $value : [];
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

        // Normalize simple text/textarea settings so the form receives a string
        if (in_array($settingKey, ['home_quote_text', 'home_quote_author', 'contact_email', 'about_vision', 'about_mission'], true)) {
            $value = $data['setting_value'] ?? null;

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

            // If the resolved value looks like a boolean or stringified boolean,
            // try to fallback to the model's stored value (prefer first array element).
            if (is_bool($value) || $value === 'true' || $value === 'false') {
                $recordVal = $this->record?->value ?? null;
                if (is_array($recordVal)) {
                    $candidate = $recordVal[0] ?? null;
                    if (filled($candidate) && is_string($candidate)) {
                        $value = $candidate;
                    }
                } elseif (is_string($recordVal) && filled($recordVal)) {
                    $value = $recordVal;
                }
            }

            $data['setting_value'] = filled($value) ? $value : null;
        }

        // Provide a preview string so the form can show the current value to admins
        $previewKeys = ['home_quote_text', 'home_quote_author', 'contact_email', 'about_vision', 'about_mission'];
        if (in_array($settingKey, $previewKeys, true)) {
            $data['setting_value_preview'] = $data['setting_value'] ?? null;
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

        if ($settingKey === 'footer_social_links') {
            $normalizedValue = $data['footer_social_links'] ?? $data['setting_value'] ?? [];

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
                ->filter(fn ($item) => is_array($item) || is_string($item))
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

        // Ensure text/textarea inputs are saved correctly for quote, contact email, and about-page content
        if (in_array($settingKey, ['home_quote_text', 'home_quote_author', 'contact_email', 'about_vision', 'about_mission'], true)) {
            $value = $data['setting_value'] ?? null;

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