<x-filament-panels::page>
    <div class="space-y-4">
        <div class="rounded-[24px] border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900">Surat Masuk</h2>
                </div>
            </div>
        </div>

        @forelse ($letters as $letter)
            <div class="rounded-[24px] border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <div class="text-lg font-semibold text-slate-900">{{ $letter->subject }}</div>
                        <div class="mt-1 text-sm text-slate-500">{{ $letter->sender }} · {{ $letter->letter_date?->format('d M Y') }}</div>
                    </div>
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-slate-700">{{ $letter->classification }}</span>
                </div>
                @if ($letter->letter_number)
                    <p class="mt-3 text-sm text-slate-600">Nomor surat: {{ $letter->letter_number }}</p>
                @endif
            </div>
        @empty
            <div class="rounded-[24px] border border-dashed border-slate-300 bg-slate-50 p-6 text-sm text-slate-600">
                Belum ada surat masuk.
            </div>
        @endforelse
    </div>
</x-filament-panels::page>
