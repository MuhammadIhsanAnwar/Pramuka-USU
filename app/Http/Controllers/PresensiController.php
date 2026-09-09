<?php

namespace App\Http\Controllers;

use App\Models\PresensiRecord;
use App\Models\PresensiSession;
use App\Models\User;
use App\Services\PresensiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Routing\Middleware\ValidateSignature;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class PresensiController extends Controller
{
    public function scan(Request $request, PresensiSession $presensiSession, string $token)
    {
        abort_unless($presensiSession->qr_token === $token, Response::HTTP_FORBIDDEN);

        if ($request->user()->presensiRecords()->where('presensi_session_id', $presensiSession->id)->exists()) {
            return redirect()->route('agenda.index')
                ->with('status', 'Anda sudah melakukan presensi untuk sesi ini.');
        }

        $relative = url()->temporarySignedRoute(
            'presensi.submit',
            now()->addHours(6),
            [
                'presensiSession' => $presensiSession->id,
                'token' => $token,
            ],
            false,
        );

        $signedSubmitUrl = rtrim(config('app.url'), '/') . $relative;

        return view('presensi.session-scan', [
            'session' => $presensiSession,
            'signedSubmitUrl' => $signedSubmitUrl,
        ]);
    }

    public function submit(Request $request, PresensiSession $presensiSession, string $token, PresensiService $presensiService): RedirectResponse
    {
        abort_unless($presensiSession->qr_token === $token, Response::HTTP_FORBIDDEN);

        $validated = $request->validate([
            'photo' => ['required', 'image', 'max:5120'],
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $latitude = (float) $validated['latitude'];
        $longitude = (float) $validated['longitude'];

        if (! $presensiService->isWithinRadius($presensiSession, $latitude, $longitude)) {
            $distance = $presensiService->getDistance($presensiSession, $latitude, $longitude);

            return back()
                ->withErrors(['latitude' => sprintf('Anda berada di luar radius presensi (%sm). Jarak saat ini %sm.', $presensiSession->radius ?? 500, $distance)])
                ->withInput();
        }

        $record = $presensiService->createPresensiRecord(
            $request->user(),
            $presensiSession,
            $request->file('photo'),
            $latitude,
            $longitude,
            $validated['notes'] ?? null,
            $request->userAgent() ?? '',
            $request->ip(),
            'qr',
        );

        if (! $record->wasRecentlyCreated) {
            return redirect()->route('agenda.index')->with('status', 'Anda sudah melakukan presensi untuk sesi ini.');
        }

        return redirect()->route('agenda.index')->with('status', 'Presensi berhasil dicatat dan terverifikasi.');
    }

    public function adminUpdateStatus(Request $request, PresensiSession $presensiSession)
    {
        $validated = $request->validate([
            'user_id' => ['required'],
            'status' => ['required', 'in:hadir,tidak'],
        ]);

        $user = User::findOrFail($validated['user_id']);

        $record = PresensiRecord::query()
            ->where('user_id', $user->id)
            ->where('presensi_session_id', $presensiSession->id)
            ->first();

        if ($validated['status'] === 'hadir') {
            if (! $record) {
                $record = PresensiRecord::create([
                    'user_id' => $user->id,
                    'presensi_session_id' => $presensiSession->id,
                    'scanned_at' => now(),
                    'status' => 'hadir',
                    'method' => 'admin',
                    'notes' => 'Presensi dibuat oleh admin.',
                ]);
            } else {
                $record->update([
                    'status' => 'hadir',
                    'scanned_at' => now(),
                    'method' => 'admin',
                ]);
            }
        } else {
            if (! $record) {
                $record = PresensiRecord::create([
                    'user_id' => $user->id,
                    'presensi_session_id' => $presensiSession->id,
                    'status' => 'tidak',
                    'scanned_at' => null,
                    'method' => null,
                    'notes' => 'Presensi tidak hadir dibuat oleh admin.',
                ]);
            } else {
                $record->update([
                    'status' => 'tidak',
                    'scanned_at' => null,
                    'method' => null,
                ]);
            }
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'statusLabel' => $validated['status'] === 'hadir' ? 'Hadir' : 'Tidak Hadir',
                'scannedAt' => $record->scanned_at?->format('d M Y H:i') ?? '-',
                'method' => $record->method ? ucfirst($record->method) : '-',
            ]);
        }

        return back()->with('status', sprintf('Status presensi %s berhasil diperbarui.', $user->name));
    }

    public function createViaGps(Request $request)
    {
        $validated = $request->validate([
            'presensi_session_id' => ['required', 'uuid'],
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
        ]);

        $presensiSession = PresensiSession::where('id', $validated['presensi_session_id'])->firstOrFail();

        $latitude = (float) $validated['latitude'];
        $longitude = (float) $validated['longitude'];

        $presensiService = app(PresensiService::class);

        if (! $presensiService->isWithinRadius($presensiSession, $latitude, $longitude)) {
            $distance = $presensiService->getDistance($presensiSession, $latitude, $longitude);

            return response()->json([
                'success' => false,
                'message' => sprintf('Anda berada di luar radius presensi (%sm). Jarak saat ini %sm.', $presensiSession->radius ?? 500, $distance),
            ], 422);
        }

        $user = $request->user();

        $status = 'hadir';
        if (isset($presensiSession->starts_at) && now()->greaterThan($presensiSession->starts_at->addMinutes(15))) {
            $status = 'terlambat';
        }

        $record = PresensiRecord::query()->firstOrCreate([
            'user_id' => $user->id,
            'presensi_session_id' => $presensiSession->id,
        ], [
            'scanned_at' => now(),
            'status' => $status,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'method' => 'gps',
            'device' => $request->userAgent() ?? '',
            'ip_address' => $request->ip(),
            'distance' => $presensiService->getDistance($presensiSession, $latitude, $longitude),
            'notes' => 'Presensi via GPS dari dashboard user',
        ]);

        if (! $record->wasRecentlyCreated) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan presensi untuk sesi ini.',
            ], 200);
        }

        return response()->json([
            'success' => true,
            'message' => 'Presensi berhasil dicatat.',
            'status' => ucfirst($record->status),
            'scanned_at' => $record->scanned_at?->format('d F Y H:i:s'),
        ]);
    }
}
