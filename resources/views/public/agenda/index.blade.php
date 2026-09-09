@extends('layouts.public')

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-24">
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <span class="section-kicker">Agenda</span>
                <h1 class="mt-5 text-4xl font-extrabold text-slate-900">Agenda Kegiatan</h1>
            </div>
            <form method="GET" action="{{ route('agenda.index') }}" class="flex items-center gap-3">
                @php
                    $defaultTypes = ['internal', 'eksternal'];
                    $types = array_values(array_unique(array_merge($defaultTypes, $availableTypes ?? [])));
                    $sel = strtolower((string) ($selectedType ?? ''));
                @endphp

                <select name="jenis" id="jenis" onchange="this.form.submit()" class="rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm" aria-label="Pilih jenis">
                    <option value="">Semua Jenis</option>
                    @foreach ($types as $type)
                        @php $t = strtolower(trim((string) $type)); @endphp
                        <option value="{{ $t }}" @selected($t === $sel)>
                            @if ($t === 'eksternal')
                                Eksternal
                            @elseif ($t === 'internal')
                                Internal
                            @else
                                {{ Str::title(str_replace(['_', '-'], ' ', $type)) }}
                            @endif
                        </option>
                    @endforeach
                </select>

                <select name="tahun" id="tahun" onchange="this.form.submit()" class="rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm" aria-label="Pilih tahun">
                    <option value="">Semua</option>
                    @foreach ($availableYears as $year)
                        <option value="{{ $year }}" @selected((string) $year === (string) $selectedYear)>{{ $year }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="mt-10 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($agendas as $agenda)
                <article class="surface-card overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <h2 class="mt-3 text-xl font-bold text-slate-900">{{ $agenda->name }}</h2>
                                @if (filled($agenda->type))
                                    <span class="inline-flex items-center rounded-full bg-[#F1F5F9] px-3 py-1 text-xs font-semibold text-slate-700">{{ Str::title($agenda->type) }}</span>
                                @endif
                            </div>
                            <p class="mt-3 text-sm text-slate-600">{{ $agenda->location }}</p>
                            <p class="mt-2 text-sm text-slate-500">{{ $agenda->starts_at?->format('d M Y, H:i') }}</p>
                            @if (filled($agenda->organizer))
                                <p class="mt-2 text-sm text-slate-500">Penyelenggara: {{ $agenda->organizer }}</p>
                            @endif
                        </div>
                </article>
            @empty
                <div class="surface-card p-6 text-sm text-slate-600">Tidak ada agenda untuk tahun yang dipilih.</div>
            @endforelse
        </div>

        <div class="mt-10">
            {{ $agendas->links() }}
        </div>
    </section>
@endsection