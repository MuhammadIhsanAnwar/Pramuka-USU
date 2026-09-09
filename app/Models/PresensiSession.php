<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PresensiSession extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'event_agenda_id',
        'name',
        'description',
        'location',
        'latitude',
        'longitude',
        'radius',
        'qr_token',
        'qr_code_path',
        'starts_at',
        'ends_at',
        'created_by',
        'status',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'radius' => 'integer',
    ];

    public function agenda(): BelongsTo
    {
        return $this->belongsTo(EventAgenda::class, 'event_agenda_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function records(): HasMany
    {
        return $this->hasMany(PresensiRecord::class, 'presensi_session_id');
    }

    public function isActive(): bool
    {
        return $this->starts_at && $this->ends_at && now()->between($this->starts_at, $this->ends_at) && $this->status === 'published';
    }

    protected static function booted(): void
    {
        static::creating(function (PresensiSession $session): void {
            if (blank($session->qr_token)) {
                $session->qr_token = (string) Str::uuid();
            }
        });
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
