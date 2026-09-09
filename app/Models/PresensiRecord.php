<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PresensiRecord extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'presensi_session_id',
        'user_id',
        'scanned_at',
        'status',
        'photo_path',
        'method',
        'device',
        'browser',
        'ip_address',
        'distance',
        'notes',
    ];

    protected $casts = [
        'scanned_at' => 'datetime',
        'distance' => 'integer',
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(PresensiSession::class, 'presensi_session_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function photoUrl(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value): ?string => blank($value) ? null : asset('storage/'.$value),
        );
    }
}
