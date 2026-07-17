<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
                        return $decoded;
                    }

                    return $raw;
                }

                return $raw;
            },
            set: static fn ($value) => ['setting_value' => is_array($value) || $value === null ? $value : [$value]],
        );
    }

    protected static function booted(): void
    {
        static::creating(function (self $setting): void {
            if (empty($setting->setting_key) && filled($setting->label)) {
                $setting->setting_key = Str::slug($setting->label);
            }
        });
    }
}