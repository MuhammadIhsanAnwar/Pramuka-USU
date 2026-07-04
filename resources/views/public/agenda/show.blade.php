@extends('layouts.public')

@section('content')
    <section class="mx-auto max-w-5xl px-4 py-16 sm:px-6 lg:px-8 lg:py-24">
        <article class="surface-card overflow-hidden">
            <img src="{{ $agenda->poster_url }}" alt="{{ $agenda->name }}" class="h-80 w-full object-cover">
            <div class="p-8 sm:p-10">
                <div class="text-xs font-semibold uppercase tracking-[0.2em] text-[#5D4037]">{{ $agenda->status }}</div>
                <h1 class="mt-4 text-4xl font-extrabold text-slate-900">{{ $agenda->name }}</h1>
                <div class="mt-4 grid gap-4 md:grid-cols-3 text-sm text-slate-600">
                    <div>
                        <div class="font-semibold text-slate-900">Lokasi</div>
                        <div>{{ $agenda->location }}</div>
                    </div>
                    <div>
                        <div class="font-semibold text-slate-900">Waktu</div>
                        <div>{{ $agenda->starts_at?->format('d M Y, H:i') }}{!! $agenda->ends_at ? ' &ndash; '.$agenda->ends_at->format('d M Y, H:i') : '' !!}</div>
                    </div>
                    <div>
                        <div class="font-semibold text-slate-900">Pembuat</div>
                        <div>{{ $agenda->creator?->name ?? 'Admin' }}</div>
                    </div>
                </div>

                <div class="mt-8 text-base leading-7 text-slate-700">
                    {!! nl2br(e($agenda->description)) !!}
                </div>
            </div>
        </article>
    </section>
@endsection
