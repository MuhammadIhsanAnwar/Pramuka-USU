@extends('layouts.public')

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-24">
        <div>
            <span class="section-kicker">Agenda</span>
            <h1 class="mt-5 text-4xl font-extrabold text-slate-900">Agenda Kegiatan</h1>
        </div>

        <div class="mt-10 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($agendas as $agenda)
                <article class="surface-card overflow-hidden">
                    <img src="{{ $agenda->poster_url }}" alt="{{ $agenda->name }}" class="h-56 w-full object-cover">
                    <div class="p-6">
                        <div class="text-xs font-semibold uppercase tracking-[0.2em] text-[#5D4037]">{{ $agenda->status }}</div>
                        <h2 class="mt-3 text-xl font-bold text-slate-900">{{ $agenda->name }}</h2>
                        <p class="mt-3 text-sm text-slate-600">{{ $agenda->location }}</p>
                        <p class="mt-2 text-sm text-slate-500">{{ $agenda->starts_at?->format('d M Y, H:i') }}</p>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="mt-10">
            {{ $agendas->links() }}
        </div>
    </section>
@endsection