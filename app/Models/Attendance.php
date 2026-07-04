<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'user_id',
        'event_agenda_id',
        'scanned_at',
        'status',
        'latitude',
        'longitude',
        'notes',
    ];

    protected $casts = [
        'scanned_at' => 'datetime',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function agenda(): BelongsTo
    {
        return $this->belongsTo(EventAgenda::class, 'event_agenda_id');
    }

    public function scopeHadir($query)
    {
        return $query->where('status', 'hadir');
    }

    protected function isHadir(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes): bool => ($attributes['status'] ?? null) === 'hadir',
        );
    }
}