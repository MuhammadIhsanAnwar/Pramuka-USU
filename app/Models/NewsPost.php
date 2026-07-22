<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class NewsPost extends Model
{
    use HasFactory;
    use HasUuids;
    use Sluggable;

    protected $fillable = [
        'news_category_id',
        'author_id',
        'title',
        'slug',
        'thumbnail_path',
        'image_paths',
        'excerpt',
        'content',
        'status',
        'published_at',
        'viewer_count',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'viewer_count' => 'integer',
        'image_paths' => 'array',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(NewsCategory::class, 'news_category_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'publish')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    protected function thumbnailUrl(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes): string => filled($attributes['thumbnail_path'] ?? null)
                ? asset('storage/'.$attributes['thumbnail_path'])
                : (filled($attributes['image_paths'] ?? null) && filled($first = data_get(json_decode($attributes['image_paths'], true), '0'))
                    ? asset('storage/'.$first)
                    : asset('images/news-placeholder.jpg')),
        );
    }

    public function getAllImageUrlsAttribute(): array
    {
        $paths = array_filter(array_merge([
            $this->thumbnail_path,
        ], $this->image_paths ?? []));

        return array_values(array_unique(array_map(
            fn (?string $path): string => asset('storage/'.$path),
            $paths,
        )));
    }

    protected function excerpt(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value, array $attributes): string => $value ?? Str::limit(strip_tags((string) ($attributes['content'] ?? '')), 160),
        );
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
            ],
        ];
    }
}