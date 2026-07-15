<?php

namespace App\Models;

use App\Models\AboutGroup;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AboutMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'about_group_id',
        'name',
        'position',
        'bio',
        'photo_path',
        'order',
        'is_active',
    ];

    protected $casts = [
        'order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(AboutGroup::class, 'about_group_id');
    }

    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo_path) {
            return asset('storage/' . ltrim($this->photo_path, '/'));
        }

        return asset('storage/logo/Logo Pramuka USU.png');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
