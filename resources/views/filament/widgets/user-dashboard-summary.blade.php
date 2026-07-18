<div class="grid gap-6 lg:grid-cols-3">
    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex items-start justify-between gap-4">
            <div>
                <div class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Profil</div>
                <div class="mt-4 text-2xl font-bold text-slate-900">{{ $user?->name }}</div>
                <div class="mt-2 text-sm text-slate-500">{{ $user?->email }}</div>
            </div>
            <div class="flex h-14 w-14 items-center justify-center rounded-full bg-[#F5F5DC] text-xl font-bold text-[#3E271A]">
                {{ strtoupper(substr($user?->name ?? 'U', 0, 1)) }}
            </div>
        </div>

        <div class="mt-6 space-y-3 text-sm text-slate-700">
            <div class="rounded-3xl bg-slate-50 p-4">Jenis user: <span class="font-semibold text-slate-900">{{ $user?->jenis_user?->value ?? $user?->jenis_user ?? '-' }}</span></div>
            <div class="rounded-3xl bg-slate-50 p-4">Presensi terkini: <span class="font-semibold text-slate-900">{{ $attendanceCount }}</span></div>
            <div class="rounded-3xl bg-slate-50 p-4">Agenda mendatang: <span class="font-semibold text-slate-900">{{ $upcomingAgendaCount }}</span></div>
            <div class="rounded-3xl bg-slate-50 p-4">Berita dibuat: <span class="font-semibold text-slate-900">{{ $newsCount }}</span></div>
        </div>
    </div>

    <div class="lg:col-span-2">
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Menu Cepat</div>
                    <div class="mt-2 text-xl font-bold text-slate-900">Akses fitur utama dengan cepat</div>
                </div>
            </div>

            <div class="mt-6 grid gap-4 sm:grid-cols-4">
                <a href="{{ url('/user/presensi') }}" class="group rounded-3xl border border-slate-200 bg-slate-50 p-5 transition hover:-translate-y-0.5 hover:border-[#3E271A]/20 hover:bg-white hover:shadow-sm">
                    <div class="text-sm font-semibold text-slate-900">Presensi</div>
                    <p class="mt-2 text-sm text-slate-500">Lihat riwayat dan status presensi Anda.</p>
                </a>
                <a href="{{ url('/user/agenda') }}" class="group rounded-3xl border border-slate-200 bg-slate-50 p-5 transition hover:-translate-y-0.5 hover:border-[#3E271A]/20 hover:bg-white hover:shadow-sm">
                    <div class="text-sm font-semibold text-slate-900">Agenda</div>
                    <p class="mt-2 text-sm text-slate-500">Lihat jadwal kegiatan dan detail acara.</p>
                </a>
                <a href="{{ url('/user/surat-masuk') }}" class="group rounded-3xl border border-slate-200 bg-slate-50 p-5 transition hover:-translate-y-0.5 hover:border-[#3E271A]/20 hover:bg-white hover:shadow-sm">
                    <div class="text-sm font-semibold text-slate-900">Surat Masuk</div>
                    <p class="mt-2 text-sm text-slate-500">Cek surat masuk terbaru dan informasi penting.</p>
                </a>
            </div>
        </div>
    </div>

    <div class="lg:col-span-3">
        <div class="grid gap-6 xl:grid-cols-3">
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Berita Saya</div>
                <div class="mt-4 space-y-3 text-sm text-slate-700">
                    @forelse ($myNews as $item)
                        <div class="rounded-3xl bg-slate-50 p-4">{{ $item->title }}</div>
                    @empty
                        <div class="text-sm text-slate-600">Belum ada berita dibuat.</div>
                    @endforelse
                </div>
            </div>
            <div class="xl:col-span-2 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Agenda Mendatang</div>
                <div class="mt-4 space-y-3">
                    @forelse ($upcomingAgendas as $agenda)
                        <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4">
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