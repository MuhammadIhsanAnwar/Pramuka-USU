<div class="p-4">
    <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-sm font-semibold text-slate-900">Detail Kegiatan</h2>
        </div>

        <div class="p-6">
            <dl class="grid grid-cols-1 gap-y-8 sm:grid-cols-2 gap-x-8">
                <div>
                    <dt class="text-xs uppercase tracking-[0.18em] text-slate-500 mb-1">Nama Kegiatan</dt>
                    <dd class="mt-3 mb-3 text-sm leading-relaxed text-slate-900">{{ $eventAgenda->name ?? '-' }}</dd>
                </div>

                <div>
                    <dt class="text-xs uppercase tracking-[0.18em] text-slate-500 mb-1">Lokasi Kegiatan</dt>
                    <dd class="mt-3 mb-3 text-sm leading-relaxed text-slate-900">{{ $eventAgenda->location ?? '-' }}</dd>
                </div>

                <div>
                    <dt class="text-xs uppercase tracking-[0.18em] text-slate-500 mb-1">Mulai Kegiatan</dt>
                    <dd class="mt-3 mb-3 text-sm leading-relaxed text-slate-900">{{ $eventAgenda->starts_at?->format('d F Y H:i:s') ?? '-' }}</dd>
                </div>

                <div>
                    <dt class="text-xs uppercase tracking-[0.18em] text-slate-500 mb-1">Selesai Kegiatan</dt>
                    <dd class="mt-3 mb-3 text-sm leading-relaxed text-slate-900">{{ $eventAgenda->ends_at?->format('d F Y H:i:s') ?? '-' }}</dd>
                </div>

                <div>
                    <dt class="text-xs uppercase tracking-[0.18em] text-slate-500 mb-1">Jenis Kegiatan</dt>
                    <dd class="mt-3 mb-3 text-sm leading-relaxed text-slate-900">{{ $eventAgenda->type ? (ucfirst($eventAgenda->type) === 'Internal' ? 'Internal' : 'Eksternal') : '-' }}</dd>
                </div>

                <div>
                    <dt class="text-xs uppercase tracking-[0.18em] text-slate-500 mb-1">Penyelenggara</dt>
                    <dd class="mt-3 mb-3 text-sm leading-relaxed text-slate-900">{{ $eventAgenda->organizer ?? '-' }}</dd>
                </div>
            </dl>
        </div>
    </div>
</div>