<?php

namespace App\Models;

use App\Enums\RoleName;
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
        'birth_place',
        'birth_date',
        'gender',
        'religion',
        'blood_type',
        'hobby',
        'siblings_count',
        'phone',
        'whatsapp_number',
        'marital_status',
        'job',
        'avatar_path',
        'address',
        'domisili_country',
        'domisili_province',
        'domisili_city',
        'domisili_district',
        'domisili_village',
        'domisili_rt',
        'domisili_rw',
        'domisili_postal_code',
        'domisili_street',
        'asal_country',
        'asal_province',
        'asal_city',
        'asal_district',
        'asal_village',
        'asal_rt',
        'asal_rw',
        'asal_postal_code',
        'asal_street',
        'education_status',
        'nim',
        'kampus',
        'fakultas',
        'program_studi',
        'father_name',
        'father_status',
        'father_address',
        'father_phone',
        'mother_name',
        'mother_status',
        'mother_address',
        'mother_phone',
        'guardian_name',
        'guardian_status',
        'guardian_address',
        'guardian_phone',
        'satuan',
        'jabatan',
        'nta',
        'tahun_masuk_pramuka_usu',
        'nama_omantaru',
        'golongan',
        'tingkatan',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'jenis_user' => UserKind::class,
        'birth_date' => 'date',
        'is_active' => 'boolean',
        'password' => 'hashed',
        'siblings_count' => 'integer',
        'tahun_masuk_pramuka_usu' => 'integer',
    ];

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

    public function needsProfileCompletion(): bool
    {
        if ($this->hasRole(RoleName::Admin->value)) {
            return false;
        }

        $requiredFields = [
            'jenis_user',
            'birth_place',
            'birth_date',
            'gender',
            'religion',
            'blood_type',
            'hobby',
            'siblings_count',
            'phone',
            'whatsapp_number',
            'marital_status',
            'job',
            'avatar_path',
            'domisili_country',
            'domisili_province',
            'domisili_city',
            'domisili_district',
            'domisili_village',
            'domisili_rt',
            'domisili_rw',
            'domisili_postal_code',
            'domisili_street',
            'asal_country',
            'asal_province',
            'asal_city',
            'asal_district',
            'asal_village',
            'asal_rt',
            'asal_rw',
            'asal_postal_code',
            'asal_street',
            'education_status',
            'nim',
            'kampus',
            'fakultas',
            'program_studi',
            'father_name',
            'father_status',
            'father_address',
            'father_phone',
            'mother_name',
            'mother_status',
            'mother_address',
            'mother_phone',
            'satuan',
            'jabatan',
            'nta',
            'tahun_masuk_pramuka_usu',
            'nama_omantaru',
            'golongan',
            'tingkatan',
        ];

        foreach ($requiredFields as $field) {
            if (! filled($this->{$field})) {
                return true;
            }
        }

        return false;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if (! $this->is_active) {
            return false;
        }

        $hasAdminRole = method_exists($this, 'hasRole') && $this->hasRole(RoleName::Admin->value);
        $hasUserRole = method_exists($this, 'hasRole') && $this->hasRole(RoleName::User->value);

        return match ($panel->getId()) {
            'admin' => $hasAdminRole,
            'user' => $hasUserRole && ! $hasAdminRole,
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
