<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'lead',
        'content',
        'photo_paths',
        'is_active',
    ];

    protected $casts = [
        'photo_paths' => 'array',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
