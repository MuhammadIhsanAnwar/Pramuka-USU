<x-filament-panels::page>
    <div class="space-y-4">
        <div class="rounded-[24px] border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900">Presensi</h2>
                </div>
            </div>
        </div>

        @forelse ($attendances as $attendance)
            <div class="rounded-[24px] border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <div class="text-lg font-semibold text-slate-900">{{ $attendance->agenda?->name ?? 'Agenda' }}</div>
                        <div class="mt-1 text-sm text-slate-500">{{ $attendance->scanned_at?->format('d M Y, H:i') }}</div>
                    </div>
                    <span class="rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-wide {{ $attendance->status === 'hadir' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                        {{ $attendance->status }}
                    </span>
                </div>
            </div>
        @empty
            <div class="rounded-[24px] border border-dashed border-slate-300 bg-slate-50 p-6 text-sm text-slate-600">
                Belum ada data presensi.
            </div>
        @endforelse
    </div>
</x-filament-panels::page>
