<div class="grid gap-6 lg:grid-cols-3">
    <div class="surface-card p-6 lg:col-span-1">
        <div class="text-sm font-semibold uppercase tracking-[0.2em] text-[#5D4037]">Profil</div>
        <div class="mt-4 text-2xl font-bold text-slate-900">{{ $user?->name }}</div>
        <div class="mt-2 text-sm text-slate-600">{{ $user?->email }}</div>
        <div class="mt-5 rounded-xl bg-[#F5F5DC]/40 p-4 text-sm text-slate-700">
            Jenis user: {{ $user?->jenis_user?->value ?? $user?->jenis_user ?? '-' }}
        </div>
    </div>

    <div class="surface-card p-6 lg:col-span-2">
        <div class="text-sm font-semibold uppercase tracking-[0.2em] text-[#5D4037]">Riwayat Presensi</div>
        <div class="mt-4 space-y-3">
            @forelse ($attendanceHistory as $item)
                <div class="rounded-xl border border-[#5D4037]/10 p-4">
                    <div class="font-semibold text-slate-900">{{ $item->agenda?->name }}</div>
                    <div class="mt-1 text-sm text-slate-500">{{ $item->scanned_at?->format('d M Y, H:i') }} · {{ ucfirst($item->status) }}</div>
                </div>
            @empty
                <div class="text-sm text-slate-600">Belum ada riwayat presensi.</div>
            @endforelse
        </div>
    </div>

    <div class="surface-card p-6 lg:col-span-3">
        <div class="grid gap-6 xl:grid-cols-3">
            <div>
                <div class="text-sm font-semibold uppercase tracking-[0.2em] text-[#5D4037]">Berita Saya</div>
                <div class="mt-4 space-y-3">
                    @forelse ($myNews as $item)
                        <div class="rounded-xl bg-[#F5F5DC]/35 p-4 text-sm text-slate-700">{{ $item->title }}</div>
                    @empty
                        <div class="text-sm text-slate-600">Belum ada berita dibuat.</div>
                    @endforelse
                </div>
            </div>
            <div class="xl:col-span-2">
                <div class="text-sm font-semibold uppercase tracking-[0.2em] text-[#5D4037]">Agenda Mendatang</div>
                <div class="mt-4 space-y-3">
                    @forelse ($upcomingAgendas as $agenda)
                        <div class="rounded-xl border border-[#5D4037]/10 p-4">
                            <div class="font-semibold text-slate-900">{{ $agenda->name }}</div>
                            <div class="mt-1 text-sm text-slate-500">{{ $agenda->starts_at?->format('d M Y, H:i') }} · {{ $agenda->location }}</div>
                        </div>
                    @empty
                        <div class="text-sm text-slate-600">Tidak ada agenda mendatang.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>