<?php

namespace App\Services;

use App\Models\PresensiRecord;
use App\Models\PresensiSession;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PresensiService
{
    public function hasRegistered(User $user, PresensiSession $session): bool
    {
        return $user->presensiRecords()->where('presensi_session_id', $session->id)->exists();
    }

    public function getDistance(PresensiSession $session, float $latitude, float $longitude): int
    {
        return $session->calculateDistance($latitude, $longitude);
    }

    public function isWithinRadius(PresensiSession $session, float $latitude, float $longitude): bool
    {
        return $this->getDistance($session, $latitude, $longitude) <= ($session->radius ?? 500);
    }

    public function createPresensiRecord(
        User $user,
        PresensiSession $session,
        UploadedFile $photo,
        float $latitude,
        float $longitude,
        ?string $notes,
        string $userAgent,
        string $ipAddress,
        string $method = 'qr',
    ): PresensiRecord {
        $photoPath = $this->storePhoto($session, $user, $photo);
        $distance = $this->getDistance($session, $latitude, $longitude);
        $status = $this->determineStatus($session);

        $record = PresensiRecord::query()->firstOrCreate(
            [
                'user_id' => $user->id,
                'presensi_session_id' => $session->id,
            ],
            [
                'scanned_at' => now(),
                'status' => $status,
            ],
        );

        if (! $record->wasRecentlyCreated) {
            return $record;
        }

        $record->forceFill([
            'photo_path' => $photoPath,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'distance' => $distance,
            'method' => $method,
            'device' => $userAgent,
            'browser' => $this->browserName($userAgent),
            'ip_address' => $ipAddress,
            'status' => $status,
            'notes' => $notes ?? 'Presensi dengan foto dan GPS',
        ])->save();

        return $record;
    }

    public function browserName(string $userAgent): string
    {
        return Str::before($userAgent, ' (');
    }

    protected function determineStatus(PresensiSession $session): string
    {
        if (! isset($session->starts_at)) {
            return 'hadir';
        }

        return now()->greaterThan($session->starts_at->addMinutes(15)) ? 'terlambat' : 'hadir';
    }

    protected function storePhoto(PresensiSession $session, User $user, UploadedFile $photo): string
    {
        $sessionName = Str::slug($session->name ?? $session->agenda?->name ?? 'presensi');
        $userName = Str::slug($user->name ?? $user->id);
        $filename = sprintf('%s.%s', $userName, $photo->extension());
        $folder = sprintf('bukti_presensi/%s', $sessionName);

        return Storage::disk('public')->putFileAs($folder, $photo, $filename);
    }
}
