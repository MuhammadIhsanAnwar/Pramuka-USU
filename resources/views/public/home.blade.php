@extends('layouts.public')

@section('content')
    <section class="w-full bg-center relative" style="background-image: url({{ asset('storage/beranda/beranda.png') }}); background-size: cover; background-position: center bottom; background-repeat: no-repeat; min-height: 420px;">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-24">
            <div class="grid gap-12 lg:grid-cols-1 lg:items-center">
                <div>
                <h1 class="mt-4 inline-block bg-[#5D4037] px-3 py-1 rounded-md font-semibold text-white" style="font-size: clamp(0.75rem, 1.2vw, 1rem);">Selamat datang di laman resmi</h1>
                <div class="mt-6 max-w-3xl flex flex-col items-start gap-1">
                    <div class="bg-[#5D4037] px-4 py-2 rounded-md text-white font-extrabold" style="font-size: clamp(1.25rem, 2vw, 2rem);">Pramuka</div>
                    <div class="bg-[#5D4037] px-4 py-3 rounded-md text-white font-extrabold" style="font-size: clamp(1.25rem, 2vw, 2rem);">Universitas Sumatera Utara</div>
                </div>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('news.index') }}" class="btn-primary">Lihat Berita</a>
                    <a href="{{ route('about') }}" class="btn-secondary">Tentang Kami</a>
                </div>
            </div>
        </div>

    </section>

    

    

    <section class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between gap-4">
            <div>
                <span class="section-kicker">Berita Terbaru</span>
                <h2 class="mt-4 text-3xl font-bold text-slate-900">Informasi kegiatan dan publikasi terbaru</h2>
            </div>
            <a href="{{ route('news.index') }}" class="hidden text-sm font-semibold text-[#5D4037] md:inline-flex">Lihat semua</a>
        </div>

        <div class="mt-8 grid gap-6 md:grid-cols-3">
            @forelse ($latestNews as $post)
                <article class="surface-card overflow-hidden">
                    <img src="{{ $post->thumbnail_url }}" alt="{{ $post->title }}" class="h-56 w-full object-cover">
                    <div class="p-6">
                        <div class="text-xs font-semibold uppercase tracking-[0.2em] text-[#5D4037]">{{ $post->category?->name ?? 'Berita' }}</div>
                        <h3 class="mt-3 text-lg font-bold text-slate-900">{{ $post->title }}</h3>
                        <p class="mt-3 text-sm leading-6 text-slate-600">{{ $post->excerpt }}</p>
                        <a href="{{ route('news.show', $post->slug) }}" class="mt-5 inline-flex text-sm font-semibold text-[#5D4037]">Baca selengkapnya</a>
                    </div>
                </article>
            @empty
                <div class="surface-card p-6 text-sm text-slate-600">Belum ada berita yang dipublikasikan.</div>
            @endforelse
        </div>
    </section>

    <section class="bg-[#5D4037]">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-10 lg:flex-row lg:items-center lg:justify-between">
                <div class="max-w-2xl">
                    <span class="section-kicker text-[#C9A227]">Sustainable Development Goals</span>
                    <h2 class="mt-3 text-3xl font-bold text-white">Peran Pramuka USU Berdampak</h2>
                </div>

                <div class="overflow-hidden rounded-3xl bg-[#8B5A2B]/95 p-3 shadow-lg shadow-slate-950/20">
                    <div class="sdgs-slider flex items-center gap-3">
                        @foreach (range(1, 17) as $goal)
                            <div class="shrink-0">
                                <img src="{{ asset(sprintf('storage/sdgs/E-WEB-Goal-%02d.png', $goal)) }}" alt="SDG {{ $goal }}" class="h-16 w-16 rounded-2xl bg-white/10 p-1 shadow-sm" />
                            </div>
                        @endforeach
                        @foreach (range(1, 17) as $goal)
                            <div class="shrink-0">
                                <img src="{{ asset(sprintf('storage/sdgs/E-WEB-Goal-%02d.png', $goal)) }}" alt="SDG {{ $goal }}" class="h-16 w-16 rounded-2xl bg-white/10 p-1 shadow-sm" />
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <style>
            .sdgs-slider {
                min-width: 200%;
                animation: sdgs-scroll 36s linear infinite;
            }
            @keyframes sdgs-scroll {
                0% { transform: translateX(0); }
                100% { transform: translateX(-50%); }
            }
        </style>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
        <div>
            <span class="section-kicker">Galeri</span>
            <h2 class="mt-4 text-3xl font-bold text-slate-900">Cuplikan dokumentasi kegiatan</h2>
        </div>

        <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($galleryItems as $item)
                <figure class="surface-card overflow-hidden">
                    <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="h-56 w-full object-cover">
                    <figcaption class="p-4 text-sm font-medium text-slate-700">{{ $item->title }}</figcaption>
                </figure>
            @empty
                <div class="surface-card p-6 text-sm text-slate-600">Belum ada foto galeri.</div>
            @endforelse
        </div>
    </section>
@endsection