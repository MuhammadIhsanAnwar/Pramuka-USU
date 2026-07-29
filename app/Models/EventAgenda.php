<?php

namespace App\Models;

use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EventAgenda extends Model
{
    use HasFactory;
    use HasUuids;
    use Sluggable;

    protected $fillable = [
        'name',
        'slug',
        'location',
        'latitude',
        'longitude',
        'radius',
        'type',
        'organizer',
        'description',
        'poster_path',
        'qr_code_path',
        'qr_token',
        'starts_at',
        'ends_at',
        'status',
        'created_by',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'radius' => 'integer',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'event_agenda_id');
    }

    public function scopePublished($query)
    {
        return $query->whereIn('status', ['published', 'publish']);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('starts_at', '>=', now());
    }

    public function scopePast($query)
    {
        return $query->where('starts_at', '<', now());
    }

    protected function isUpcoming(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes): bool => isset($attributes['starts_at'])
                ? Carbon::parse($attributes['starts_at'])->isFuture()
                : false,
        );
    }

    protected function posterUrl(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes): string => blank($attributes['poster_path'] ?? null)
                ? asset('images/agenda-placeholder.jpg')
                : asset('storage/'.$attributes['poster_path']),
        );
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }

    public function calculateDistance(float $latitude, float $longitude): int
    {
        if (! isset($this->latitude, $this->longitude)) {
            return 0;
        }

        $earthRadius = 6371000;
        $latFrom = deg2rad($this->latitude);
        $lngFrom = deg2rad($this->longitude);
        $latTo = deg2rad($latitude);
        $lngTo = deg2rad($longitude);

        $latDelta = $latTo - $latFrom;
        $lngDelta = $lngTo - $lngFrom;

        $a = sin($latDelta / 2) ** 2 + cos($latFrom) * cos($latTo) * sin($lngDelta / 2) ** 2;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return (int) round($earthRadius * $c);
    }
}
