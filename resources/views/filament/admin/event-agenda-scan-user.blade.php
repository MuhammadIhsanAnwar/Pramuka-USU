<x-filament::page>
    <div class="space-y-6 py-6">
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <h1 class="text-2xl font-semibold text-slate-900">Scan QR User</h1>
            <p class="mt-2 text-sm text-slate-600">Gunakan URL presensi di bawah ini untuk melakukan scan atau bagikan kepada peserta yang berwenang.</p>
            <div class="mt-6 rounded-3xl border border-slate-200 bg-slate-50 p-5 text-sm text-slate-700">
                <div class="mb-3 font-semibold text-slate-900">URL Presensi</div>
                <div class="break-all text-xs text-slate-500">{{ $url }}</div>
            </div>
            @if (filled($eventAgenda->qr_code_path))
                <div class="mt-6 rounded-3xl border border-slate-200 bg-white p-5 text-sm text-slate-700">
                    <div class="mb-3 font-semibold text-slate-900">QR Presensi</div>
                    <div class="mx-auto flex h-64 w-64 items-center justify-center rounded-3xl bg-slate-50 p-4">
                        <img src="{{ asset('storage/'.$eventAgenda->qr_code_path) }}" alt="QR Presensi {{ $eventAgenda->name }}" class="h-full w-full object-contain" />
                    </div>
                </div>
            @endif
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ $url }}" target="_blank" class="inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500">Buka URL Presensi</a>
                <button type="button" onclick="navigator.clipboard.writeText('{{ $url }}')" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50">Salin Link</button>
            </div>
        </div>
    </div>
</x-filament::page>
