<div class="space-y-6">
    <div class="grid gap-4 lg:grid-cols-2">
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-slate-900">Informasi Agenda</h2>
            <p class="mt-2 text-sm text-slate-600">Agenda hanya menyimpan metadata kegiatan. Pengaturan presensi seperti GPS dan radius dapat dikelola dari tombol "Atur Presensi" di atas.</p>
            <dl class="mt-5 grid gap-4 sm:grid-cols-2">
                <div>
                    <dt class="text-xs uppercase tracking-[0.18em] text-slate-500">Agenda</dt>
                    <dd class="mt-2 text-base font-semibold text-slate-900">{{ $eventAgenda->name }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-[0.18em] text-slate-500">Status</dt>
                    <dd class="mt-2">
                        <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-emerald-700">{{ ucfirst($eventAgenda->status) }}</span>
                    </dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-[0.18em] text-slate-500">Lokasi</dt>
                    <dd class="mt-2 text-slate-900">{{ $eventAgenda->location }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-[0.18em] text-slate-500">Radius</dt>
                    <dd class="mt-2 text-slate-900">{{ $eventAgenda->radius ?? 500 }} meter</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-[0.18em] text-slate-500">Mulai</dt>
                    <dd class="mt-2 text-slate-900">{{ $eventAgenda->starts_at?->format('d M Y H:i') }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-[0.18em] text-slate-500">Selesai</dt>
                    <dd class="mt-2 text-slate-900">{{ $eventAgenda->ends_at?->format('d M Y H:i') ?? '-' }}</dd>
                </div>
            </dl>
        </div>
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-slate-900">Statistik Kehadiran</h2>
            <p class="mt-2 text-sm text-slate-600">Ringkasan status kehadiran peserta untuk agenda ini.</p>
            <div class="mt-6 space-y-4">
                @php
                    $total = $users->count();
                    $hadir = $users->filter(fn ($user) => $user->attendances->first()?->status === 'hadir')->count();
                    $terlambat = $users->filter(fn ($user) => $user->attendances->first()?->status === 'terlambat')->count();
                    $belum = $total - $hadir - $terlambat;
                    $percentage = $total > 0 ? round(($hadir / $total) * 100) : 0;
                @endphp
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-700">Hadir</span>
                        <span class="text-sm font-semibold text-slate-900">{{ $hadir }}</span>
                    </div>
                    <div class="h-2 overflow-hidden rounded-full bg-slate-100">
                        <div class="h-full rounded-full bg-emerald-500" style="width: {{ $percentage }}%"></div>
                    </div>
                </div>
                <div class="grid gap-3 sm:grid-cols-3">
                    <div class="rounded-2xl bg-slate-50 p-4 text-center">
                        <div class="text-xs uppercase tracking-[0.18em] text-slate-500">Total Peserta</div>
                        <div class="mt-2 text-2xl font-semibold text-slate-900">{{ $total }}</div>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4 text-center">
                        <div class="text-xs uppercase tracking-[0.18em] text-slate-500">Terlambat</div>
                        <div class="mt-2 text-2xl font-semibold text-amber-700">{{ $terlambat }}</div>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4 text-center">
                        <div class="text-xs uppercase tracking-[0.18em] text-slate-500">Belum Hadir</div>
                        <div class="mt-2 text-2xl font-semibold text-rose-700">{{ $belum }}</div>
                    </div>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-4">
                    <div class="flex items-center justify-between text-sm text-slate-500">
                        <span>Kehadiran</span>
                        <span>{{ $percentage }}%</span>
                    </div>
                    <div class="mt-3 h-3 overflow-hidden rounded-full bg-slate-100">
                        <div class="h-full rounded-full bg-emerald-600" style="width: {{ $percentage }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-semibold text-slate-900">Peserta</h3>
                <p class="mt-1 text-sm text-slate-500">Kelola seluruh user dan status presensi.</p>
            </div>
        </div>
        <div class="mt-6 overflow-hidden rounded-3xl border border-slate-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm text-slate-700">
                    <thead class="bg-slate-50 text-slate-700">
                        <tr>
                            <th class="px-4 py-3">User</th>
                            <th class="px-4 py-3">Satuan</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Jam Presensi</th>
                            <th class="px-4 py-3">Metode</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach ($users as $user)
                            @php
                                $attendance = $user->attendances->first();
                            @endphp
                            <tr>
                                <td class="px-4 py-4 font-medium text-slate-900">{{ $user->name }}</td>
                                <td class="px-4 py-4">{{ $user->satuan }}</td>
                                <td class="px-4 py-4">
                                    @if ($attendance?->status === 'hadir')
                                        <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-emerald-700">Hadir</span>
                                    @elseif ($attendance?->status === 'terlambat')
                                        <span class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-amber-700">Terlambat</span>
                                    @else
                                        <span class="inline-flex rounded-full bg-rose-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-rose-700">Belum Hadir</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4">{{ $attendance?->scanned_at?->format('d M Y H:i') ?? '-' }}</td>
                                <td class="px-4 py-4">{{ $attendance?->method ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
