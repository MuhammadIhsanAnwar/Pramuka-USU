<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\EventAgenda;
use App\Models\User;
use App\Services\AttendanceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class AttendanceController extends Controller
{
    public function scan(Request $request, EventAgenda $eventAgenda, string $token)
    {
        abort_unless($eventAgenda->qr_token === $token, Response::HTTP_FORBIDDEN);

        if ($request->user()->attendances()->where('event_agenda_id', $eventAgenda->id)->exists()) {
            return redirect()->route('agenda.index')
                ->with('status', 'Anda sudah melakukan presensi untuk agenda ini.');
        }

        $signedSubmitUrl = url()->temporarySignedRoute(
            'attendance.submit',
            now()->addHours(6),
            [
                'eventAgenda' => $eventAgenda->slug,
                'token' => $token,
            ],
        );

        return view('presensi.scan', [
            'eventAgenda' => $eventAgenda,
            'signedSubmitUrl' => $signedSubmitUrl,
        ]);
    }

    public function submit(Request $request, EventAgenda $eventAgenda, string $token, AttendanceService $attendanceService): RedirectResponse
    {
        abort_unless($eventAgenda->qr_token === $token, Response::HTTP_FORBIDDEN);

        $validated = $request->validate([
            'photo' => ['required', 'image', 'max:5120'],
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $latitude = (float) $validated['latitude'];
        $longitude = (float) $validated['longitude'];

        if (! $attendanceService->isWithinRadius($eventAgenda, $latitude, $longitude)) {
            $distance = $attendanceService->getDistance($eventAgenda, $latitude, $longitude);

            return back()
                ->withErrors(['latitude' => sprintf('Anda berada di luar radius presensi (%sm). Jarak saat ini %sm.', $eventAgenda->radius ?? 500, $distance)])
                ->withInput();
        }

        $attendance = $attendanceService->createAttendance(
            $request->user(),
            $eventAgenda,
            $request->file('photo'),
            $latitude,
            $longitude,
            $validated['notes'] ?? null,
            $request->userAgent() ?? '',
            $request->ip(),
        );

        if (! $attendance->wasRecentlyCreated) {
            return redirect()->route('agenda.index')->with('status', 'Anda sudah melakukan presensi untuk agenda ini.');
        }

        return redirect()->route('agenda.index')->with('status', 'Presensi berhasil dicatat dan terverifikasi.');
    }

    public function adminUpdateStatus(Request $request, EventAgenda $eventAgenda)
    {
        $validated = $request->validate([
            'user_id' => ['required'],
            // accept user id as numeric or uuid, and status values from admin UI
            'status' => ['required', 'in:hadir,tidak'],
        ]);

        $user = User::findOrFail($validated['user_id']);

        $attendance = Attendance::query()
            ->where('user_id', $user->id)
            ->where('event_agenda_id', $eventAgenda->id)
            ->first();

        if ($validated['status'] === 'hadir') {
            if (! $attendance) {
                $attendance = Attendance::create([
                    'user_id' => $user->id,
                    'event_agenda_id' => $eventAgenda->id,
                    'scanned_at' => now(),
                    'status' => 'hadir',
                    'method' => 'admin',
                    'notes' => 'Presensi dibuat oleh admin.',
                ]);
            } else {
                $attendance->update([
                    'status' => 'hadir',
                    'scanned_at' => now(),
                    'method' => 'admin',
                ]);
            }
        } else {
            if ($attendance) {
                $attendance->update([
                    'status' => 'tidak',
                    'method' => null,
                ]);
            }
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'statusLabel' => $validated['status'] === 'hadir' ? 'Hadir' : 'Tidak Hadir',
                'scannedAt' => $attendance?->scanned_at?->format('d M Y H:i') ?? '-',
                'method' => $attendance?->method ? ucfirst($attendance->method) : '-',
            ]);
        }

        return back()->with('status', sprintf('Status presensi %s berhasil diperbarui.', $user->name));
    }

    public function createViaGps(Request $request)
    {
        $validated = $request->validate([
            'event_agenda_id' => ['required', 'uuid'],
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
        ]);

        $eventAgenda = EventAgenda::where('id', $validated['event_agenda_id'])->firstOrFail();

        $latitude = (float) $validated['latitude'];
        $longitude = (float) $validated['longitude'];

        $attendanceService = app(\App\Services\AttendanceService::class);

        if (! $attendanceService->isWithinRadius($eventAgenda, $latitude, $longitude)) {
            $distance = $attendanceService->getDistance($eventAgenda, $latitude, $longitude);

            return response()->json([
                'success' => false,
                'message' => sprintf('Anda berada di luar radius presensi (%sm). Jarak saat ini %sm.', $eventAgenda->radius ?? 500, $distance),
            ], 422);
        }

        $user = $request->user();

        $status = 'hadir';
        if (isset($eventAgenda->starts_at) && now()->greaterThan($eventAgenda->starts_at->addMinutes(15))) {
            $status = 'terlambat';
        }

        $attendance = Attendance::query()->firstOrCreate([
            'user_id' => $user->id,
            'event_agenda_id' => $eventAgenda->id,
        ], [
            'scanned_at' => now(),
            'status' => $status,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'method' => 'gps',
            'device' => $request->userAgent() ?? '',
            'ip_address' => $request->ip(),
            'distance' => $attendanceService->getDistance($eventAgenda, $latitude, $longitude),
            'notes' => 'Presensi via GPS dari dashboard user',
        ]);

        if (! $attendance->wasRecentlyCreated) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan presensi untuk agenda ini.',
            ], 200);
        }

        return response()->json([
            'success' => true,
            'message' => 'Presensi berhasil dicatat.',
            'status' => ucfirst($attendance->status),
            'scanned_at' => $attendance->scanned_at?->format('d F Y H:i:s'),
        ]);
    }
}