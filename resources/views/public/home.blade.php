@extends('layouts.public')

@section('content')
<section class="w-full bg-center relative overflow-hidden" style="background-image: url({{ asset('images/default-hero.jpg') }}); background-size: cover; background-position: center bottom; background-repeat: no-repeat; min-height: 420px;">
    <div class="absolute inset-0 bg-slate-950/10"></div>
    <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-24 relative z-10">
        <div class="grid gap-12 lg:grid-cols-2 lg:items-center">
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
            <div class="flex flex-col items-center justify-end gap-5">
                <div class="w-full max-w-md overflow-hidden rounded-[2rem] bg-white/10 shadow-[0_30px_60px_-20px_rgba(15,23,42,0.65)] ring-1 ring-white/10 backdrop-blur-xl">
                    <div class="flex h-full min-h-[260px] w-full items-center justify-center rounded-[2rem] bg-[#5D4037] px-6 text-center text-white" style="border-radius: 2rem;">
                        <div>
                            <div class="text-xl font-semibold">Pramuka USU</div>
                            <div class="mt-2 text-sm text-[#F9E4A6]">Video promosi akan tersedia segera.</div>
                        </div>
                    </div>
                </div>
                <div class="mx-auto w-max overflow-hidden rounded-full bg-white px-4 py-3 shadow-[0_20px_45px_-20px_rgba(15,23,42,0.25)] ring-1 ring-slate-300/80 backdrop-blur-sm">
                    <div class="flex items-center justify-center gap-1.5 whitespace-nowrap">
                        <div class="text-sm font-semibold text-[#5D4037]">Logo</div>
                        <div class="text-sm font-semibold text-[#5D4037]">Pramuka USU</div>
                        <div class="text-sm font-semibold text-[#5D4037]">USU</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
    <div class="flex items-end justify-between gap-4">
        <div>
            <span class="section-kicker">Berita Terbaru</span>
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
                <span class="section-kicker text-[#C9A227]">SDGS</span>
                <h2 class="mt-2 text-3xl font-bold text-white">Peran Pramuka USU Berdampak</h2>
            </div>

            <div class="w-full max-w-4xl overflow-hidden rounded-[2rem] bg-[#8B5A2B]/95 p-2 shadow-lg shadow-slate-950/20">
                <div class="sdgs-slider flex items-center gap-2">
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
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-50%);
            }
        }
    </style>
</section>

<section class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
    <div>
        <span class="section-kicker">Galeri</span>
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

<section class="overflow-hidden bg-[#5D4037]">
    <div class="grid gap-10 px-4 py-16 sm:px-6 lg:mx-auto lg:grid-cols-2 lg:max-w-7xl lg:items-center">

        {{-- Text --}}
        <div class="flex items-center">
            <div class="mx-auto w-full max-w-xl px-0 lg:px-12">

                <span class="section-kicker">Kutipan</span>

                <h2 class="mt-6 max-w-3xl text-3xl font-semibold leading-tight text-white lg:text-4xl">
                    “We never fail when we try to do our duty, we always fail when we neglect to do it.”
                </h2>

                <p class="mt-4 text-lg font-medium text-[#F9E4A6]">
                    — Lord Baden Powell
                </p>

                <div class="mt-8 h-1 w-24 rounded-full bg-[#C9A227]"></div>

            </div>
        </div>

        {{-- Image --}}
        <div class="flex items-end justify-end">
            <img
                src="{{ asset('images/default-avatar.png') }}"
                alt="Lord Baden Powell"
                class="max-h-[14rem] w-auto rounded-[1.5rem] object-contain" />
        </div>
    </div>
</section>

@endsection