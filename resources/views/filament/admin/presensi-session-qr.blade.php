<div class="space-y-4 py-4">
    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="text-sm font-semibold text-slate-900">QR Presensi</div>
        <div class="mt-4">
            <div class="mb-3 text-sm text-slate-700">Gunakan QR presensi di bawah ini untuk membuka halaman presensi.</div>
            <div class="mx-auto flex h-64 w-64 items-center justify-center rounded-3xl bg-slate-50 p-4">
                @if (filled($session->qr_code_path))
                    <img src="{{ asset('storage/'.$session->qr_code_path) }}" alt="QR Presensi" class="h-full w-full object-contain" />
                @else
                    <div class="text-slate-500">QR belum dihasilkan.</div>
                @endif
            </div>
        </div>
        <div class="mt-4 text-sm text-slate-500 break-all">{{ $signedUrl }}</div>
    </div>
</div>
