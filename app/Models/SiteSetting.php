<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory;

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
            get: static fn ($value, array $attributes) => $attributes['setting_value'] ?? null,
            set: static fn ($value) => ['setting_value' => is_array($value) ? $value : [$value]],
        );
    }
}