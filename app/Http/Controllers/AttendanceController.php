<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\EventAgenda;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function scan(Request $request, EventAgenda $eventAgenda, string $token): RedirectResponse
    {
        abort_unless($eventAgenda->qr_token === $token, 403);

        $attendance = Attendance::query()->firstOrCreate(
            [
                'user_id' => $request->user()->id,
                'event_agenda_id' => $eventAgenda->id,
            ],
            [
                'scanned_at' => now(),
                'status' => 'hadir',
                'latitude' => $request->query('lat'),
                'longitude' => $request->query('lng'),
                'notes' => 'Presensi QR otomatis',
            ],
        );

        if (! $attendance->wasRecentlyCreated) {
            return redirect()->route('agenda.index')->with('status', 'Anda sudah melakukan presensi untuk agenda ini.');
        }

        return redirect()->route('agenda.index')->with('status', 'Presensi berhasil dicatat.');
    }
}