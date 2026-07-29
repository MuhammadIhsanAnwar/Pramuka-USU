<div class="space-y-6 text-center">
    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6 shadow-sm">
        <div class="mx-auto inline-flex h-64 w-64 items-center justify-center rounded-3xl bg-white p-6 shadow-inner">
            <img src="{{ asset('storage/'.$eventAgenda->qr_code_path) }}" alt="QR Presensi {{ $eventAgenda->name }}" class="h-full w-full object-contain" />
        </div>
    </div>
    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <h3 class="text-lg font-semibold text-slate-900">Token QR Presensi</h3>
        <p class="mt-2 text-sm text-slate-600">Token ini memiliki masa berlaku sementara untuk keamanan. QR dapat diperbarui oleh sistem jika diperlukan.</p>
        <div class="mt-4 rounded-2xl bg-slate-50 p-4 text-left text-sm text-slate-700">
            <strong>URL Presensi:</strong>
            <div class="break-all text-xs text-slate-500">{{ $signedUrl }}</div>
        </div>
    </div>
</div>
