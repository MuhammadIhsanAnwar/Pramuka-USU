<?php

namespace App\Models;

use App\Enums\UserKind;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory;
    use HasRoles;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'jenis_user',
        'avatar_path',
        'phone',
        'birth_date',
        'bio',
        'address',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'jenis_user' => UserKind::class,
            'birth_date' => 'date',
            'is_active' => 'boolean',
            'password' => 'hashed',
        ];
    }

    public function newsPosts(): HasMany
    {
        return $this->hasMany(NewsPost::class, 'author_id');
    }

    public function galleries(): HasMany
    {
        return $this->hasMany(Gallery::class, 'uploaded_by');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function eventAgendas(): HasMany
    {
        return $this->hasMany(EventAgenda::class, 'created_by');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePembina($query)
    {
        return $query->where('jenis_user', UserKind::Pembina->value);
    }

    public function scopePesertaDidik($query)
    {
        return $query->where('jenis_user', UserKind::PesertaDidik->value);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if (! $this->is_active) {
            return false;
        }

        return match ($panel->getId()) {
            'admin' => $this->hasRole('Admin'),
            'user' => $this->hasRole('User') || $this->hasRole('Admin'),
            default => false,
        };
    }

    protected function email(): Attribute
    {
        return Attribute::make(
            set: static fn (string $value): string => Str::lower(trim($value)),
        );
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            set: static fn (string $value): string => trim($value),
        );
    }

    protected function avatarUrl(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes): string => blank($attributes['avatar_path'] ?? null)
                ? asset('images/default-avatar.png')
                : asset('storage/'.$attributes['avatar_path']),
        );
    }
}
