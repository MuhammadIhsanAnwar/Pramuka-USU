<x-filament-panels::page>
    <div class="space-y-4">
        <div class="rounded-[24px] border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900">Agenda</h2>
                </div>
            </div>
        </div>

        @forelse ($agendas as $agenda)
            <div class="rounded-[24px] border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <div class="text-lg font-semibold text-slate-900">{{ $agenda->name }}</div>
                        <div class="mt-1 text-sm text-slate-500">{{ $agenda->starts_at?->format('d M Y, H:i') }} · {{ $agenda->location }}</div>
                    </div>
                    <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-amber-700">{{ $agenda->status }}</span>
                </div>
                @if ($agenda->description)
                    <p class="mt-3 text-sm text-slate-600">{{ $agenda->description }}</p>
                @endif
            </div>
        @empty
            <div class="rounded-[24px] border border-dashed border-slate-300 bg-slate-50 p-6 text-sm text-slate-600">
                Belum ada agenda tersedia.
            </div>
        @endforelse
    </div>
</x-filament-panels::page>
