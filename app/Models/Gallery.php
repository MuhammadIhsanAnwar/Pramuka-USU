<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gallery extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'title',
        'album',
        'image_path',
        'description',
        'uploaded_by',
    ];

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function scopeAlbum($query, string $album)
    {
        return $query->where('album', $album);
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes): string => blank($attributes['image_path'] ?? null)
                ? asset('images/gallery-placeholder.jpg')
                : asset('storage/'.$attributes['image_path']),
        );
    }
}