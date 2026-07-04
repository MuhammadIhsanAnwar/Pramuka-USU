<?php

namespace App\Observers;

use App\Models\EventAgenda;
use App\Services\QrCodeService;
use Illuminate\Support\Str;

class EventAgendaObserver
{
    public function __construct(private readonly QrCodeService $qrCodeService)
    {
    }

    public function saved(EventAgenda $eventAgenda): void
    {
        $qrToken = $eventAgenda->qr_token ?? (string) Str::uuid();

        $scanUrl = route('attendance.scan', [
            'eventAgenda' => $eventAgenda->slug,
            'token' => $qrToken,
        ]);

        $qrPath = 'agendas/qr/'.$eventAgenda->slug.'.svg';

        $eventAgenda->forceFill([
            'qr_token' => $qrToken,
            'qr_code_path' => $this->qrCodeService->generateSvg($scanUrl, $qrPath),
        ])->saveQuietly();
    }
}