<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class SiteSetting extends Model
{
    use HasFactory;

    public static function ensureDefaultSettings(): void
    {
        $defaults = [
            'home_background_image' => [
                'setting_group' => 'home',
                'label' => 'Gambar Latar Beranda',
                'setting_type' => 'image',
                'setting_value' => ['/storage/beranda/Beranda.png'],
                'is_public' => true,
            ],
            'intro_video' => [
                'setting_group' => 'home',
                'label' => 'Video Intro Beranda',
                'setting_type' => 'video',
                'setting_value' => ['/storage/beranda/Intro.mp4'],
                'is_public' => true,
            ],
            'home_brand_logos' => [
                'setting_group' => 'home',
                'label' => 'Logo Beranda Bawah Video',
                'setting_type' => 'image',
                'setting_value' => [],
                'is_public' => true,
            ],
        ];

        foreach ($defaults as $key => $data) {
            static::query()->firstOrCreate(
                ['setting_key' => $key],
                array_merge(['setting_key' => $key], $data),
            );
        }
    }

    protected $fillable = [
        'setting_key',
        'setting_group',
        'label',
        'setting_type',
        'setting_value',
        'is_public',
    ];

    protected $casts = [
        'setting_value' => 'array',
        'is_public' => 'boolean',
    ];

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    protected function value(): Attribute
    {
        return Attribute::make(
            get: static function ($value, array $attributes) {
                $raw = $attributes['setting_value'] ?? null;

                if ($raw === null) {
                    return null;
                }

                if (is_string($raw)) {
                    $decoded = json_decode($raw, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $raw = $decoded;
                    } else {
                        return $raw;
                    }
                }

                if (($attributes['setting_key'] ?? null) === 'home_brand_logos') {
                    return collect($raw ?? [])
                        ->map(function ($item) {
                            if (is_array($item)) {
                                if (array_key_exists('path', $item)) {
                                    return $item['path'];
                                }

                                if (array_key_exists('file', $item)) {
                                    return $item['file'];
                                }

                                return $item[0] ?? null;
                            }

                            return $item;
                        })
                        ->filter(fn ($item) => filled($item))
                        ->map(function ($item) {
                            if (is_string($item)) {
                                return self::normalizeValuePath($item);
                            }

                            return $item;
                        })
                        ->values()
                        ->all();
                }

                return $raw;
            },
            set: static function ($value, array $attributes) {
                $value = self::decodeJsonValue($value);

                if (($attributes['setting_key'] ?? null) === 'home_brand_logos') {
                    $normalized = collect(is_array($value) ? $value : ($value === null ? [] : [$value]))
                        ->map(function ($item) {
                            if (is_array($item)) {
                                if (array_key_exists('path', $item)) {
                                    return $item['path'];
                                }

                                if (array_key_exists('file', $item)) {
                                    return $item['file'];
                                }

                                return $item[0] ?? null;
                            }

                            return $item;
                        })
                        ->filter(fn ($item) => filled($item))
                        ->map(function ($item) {
                            if (is_string($item)) {
                                return self::normalizeValuePath($item);
                            }

                            return $item;
                        })
                        ->values()
                        ->all();

                    return ['setting_value' => $normalized];
                }

                if (in_array($attributes['setting_key'] ?? null, ['home_background_image', 'intro_video'], true)) {
                    $normalized = collect(is_array($value) ? $value : ($value === null ? [] : [$value]))
                        ->filter(fn ($item) => filled($item))
                        ->map(function ($item) {
                            if (is_string($item)) {
                                return self::normalizeValuePath($item);
                            }

                            return null;
                        })
                        ->filter(fn ($item) => filled($item))
                        ->values()
                        ->all();

                    return ['setting_value' => $normalized];
                }

                return ['setting_value' => is_array($value) || $value === null ? $value : [$value]];
            },
        );
    }

    protected static function normalizeValuePath(string $path): string
    {
        $normalizedPath = str_replace('\\', '/', trim($path));
        $normalizedPath = preg_replace('#^(?:public/)?storage/(?:app/public/)?#', '', $normalizedPath) ?? $normalizedPath;
        $normalizedPath = ltrim($normalizedPath, '/');

        if (Str::startsWith($normalizedPath, 'home_brand_logo/')) {
            $normalizedPath = preg_replace('#^home_brand_logo/#', 'home_brand_logos/', $normalizedPath);
        }

        if (Str::startsWith($normalizedPath, 'logo/')) {
            $normalizedPath = preg_replace('#^logo/#', 'home_brand_logos/', $normalizedPath);
        }

        if (Str::startsWith($normalizedPath, 'home_brand_logos/')) {
            return $normalizedPath;
        }

        if (Str::startsWith($normalizedPath, 'beranda/')) {
            return $normalizedPath;
        }

        return $normalizedPath;
    }

    protected static function decodeJsonValue(mixed $value): mixed
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $decoded;
            }
        }

        return $value;
    }

    protected static function booted(): void
    {
        static::creating(function (self $setting): void {
            if (empty($setting->setting_key) && filled($setting->label)) {
                $setting->setting_key = Str::slug($setting->label);
            }
        });

        static::saving(function (self $setting): void {
            if (($setting->setting_key ?? null) === 'home_brand_logos') {
                $setting->setting_value = collect(self::decodeJsonValue($setting->setting_value))
                    ->map(function ($item) {
                        if (is_array($item)) {
                            if (array_key_exists('path', $item)) {
                                return $item['path'];
                            }

                            if (array_key_exists('file', $item)) {
                                return $item['file'];
                            }

                            return $item[0] ?? null;
                        }

                        return $item;
                    })
                    ->filter(fn ($item) => filled($item))
                    ->map(function ($item) {
                        if (is_string($item)) {
                            return self::normalizeValuePath($item);
                        }

                        return $item;
                    })
                    ->values()
                    ->all();
            }
        });

        static::saved(function (self $setting): void {
            if (Str::startsWith($setting->setting_key ?? '', 'home_')) {
                Cache::forget('public.home.data');
            }
        });
    }
}