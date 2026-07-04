@extends('layouts.public')

@section('content')
    <section class="mx-auto max-w-5xl px-4 py-16 sm:px-6 lg:px-8 lg:py-24">
        <article class="surface-card overflow-hidden">
            <img src="{{ $gallery->image_url }}" alt="{{ $gallery->title }}" class="h-96 w-full object-cover">
            <div class="p-8 sm:p-10">
                <div class="text-xs font-semibold uppercase tracking-[0.2em] text-[#5D4037]">{{ $gallery->album ?? 'Umum' }}</div>
                <h1 class="mt-4 text-4xl font-extrabold text-slate-900">{{ $gallery->title }}</h1>
                <div class="mt-4 text-sm text-slate-600">
                    <div>Diunggah oleh {{ $gallery->uploader?->name ?? 'Admin' }}</div>
                </div>
                <div class="mt-8 text-base leading-7 text-slate-700">
                    {!! nl2br(e($gallery->description ?? 'Tidak ada deskripsi.')) !!}
                </div>
            </div>
        </article>
    </section>
@endsection
