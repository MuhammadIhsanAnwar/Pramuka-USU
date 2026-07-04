@extends('layouts.public')

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-24">
        <div>
            <span class="section-kicker">Galeri</span>
            <h1 class="mt-5 text-4xl font-extrabold text-slate-900">Dokumentasi Kegiatan</h1>
        </div>

        <div class="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @foreach ($galleries as $item)
                <figure class="surface-card overflow-hidden">
                    <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="h-56 w-full object-cover">
                    <figcaption class="p-4">
                        <div class="text-sm font-semibold text-slate-900">{{ $item->title }}</div>
                        <div class="mt-1 text-xs text-slate-500">{{ $item->album ?? 'Umum' }}</div>
                    </figcaption>
                </figure>
            @endforeach
        </div>

        <div class="mt-10">
            {{ $galleries->links() }}
        </div>
    </section>
@endsection