<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\EventAgenda;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AttendanceService
{
    public function hasRegistered(User $user, EventAgenda $eventAgenda): bool
    {
        return $user->attendances()->where('event_agenda_id', $eventAgenda->id)->exists();
    }

    public function getDistance(EventAgenda $eventAgenda, float $latitude, float $longitude): int
    {
        return $eventAgenda->calculateDistance($latitude, $longitude);
    }

    public function isWithinRadius(EventAgenda $eventAgenda, float $latitude, float $longitude): bool
    {
        return $this->getDistance($eventAgenda, $latitude, $longitude) <= ($eventAgenda->radius ?? 500);
    }

    public function createAttendance(
        User $user,
        EventAgenda $eventAgenda,
        UploadedFile $photo,
        float $latitude,
        float $longitude,
        ?string $notes,
        string $userAgent,
        string $ipAddress,
    ): Attendance {
        $photoPath = $this->storePhoto($photo);
        $distance = $this->getDistance($eventAgenda, $latitude, $longitude);

        $status = $this->determineStatus($eventAgenda);

        $attendance = Attendance::query()->firstOrCreate(
            [
                'user_id' => $user->id,
                'event_agenda_id' => $eventAgenda->id,
            ],
            [
                'scanned_at' => now(),
                'status' => $status,
            ],
        );

        if (! $attendance->wasRecentlyCreated) {
            return $attendance;
        }

        $attendance->forceFill([
            'photo_path' => $photoPath,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'distance' => $distance,
            'method' => 'qr',
            'device' => $userAgent,
            'browser' => $this->browserName($userAgent),
            'ip_address' => $ipAddress,
            'status' => $status,
            'notes' => $notes ?? 'Presensi QR dengan foto dan GPS',
        ])->save();

        return $attendance;
    }

    public function browserName(string $userAgent): string
    {
        return Str::before($userAgent, ' (');
    }

    protected function determineStatus(EventAgenda $eventAgenda): string
    {
        if (! isset($eventAgenda->starts_at)) {
            return 'hadir';
        }

        return now()->greaterThan($eventAgenda->starts_at->addMinutes(15)) ? 'terlambat' : 'hadir';
    }

    protected function storePhoto(UploadedFile $photo): string
    {
        return $photo->storePublicly('attendances/photos', 'public');
    }
}
