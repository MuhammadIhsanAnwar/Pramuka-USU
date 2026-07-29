<x-filament-panels::page>
    <div class="space-y-4">
        <div class="rounded-[24px] border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div class="flex gap-3">
                    @php
                        $scanEventAgenda = optional($attendances->first()?->agenda);
                        $scanEventSlug = $scanEventAgenda?->slug;
                        $scanEventToken = $scanEventAgenda?->qr_token;
                    @endphp
                    @if ($scanEventSlug && filled($scanEventToken))
                        <a href="{{ route('attendance.scan', ['eventAgenda' => $scanEventSlug, 'token' => $scanEventToken]) }}" class="btn btn-primary">Scan QR Presensi</a>
                    @else
                        <button type="button" class="btn btn-primary opacity-50 cursor-not-allowed" disabled>Scan QR Presensi</button>
                    @endif
                    <a href="{{ route('user.qr') }}" class="btn">Tunjukkan QR User</a>
                </div>
            </div>
        </div>

        <div class="rounded-[24px] border border-slate-200 bg-white p-5 shadow-sm">
            {{ $this->table }}
        </div>
    </div>
</x-filament-panels::page>