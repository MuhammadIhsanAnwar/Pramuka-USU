@extends('layouts.public')

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-24">
        <div class="grid gap-12 lg:grid-cols-2 lg:items-center">
            <div>
                <span class="section-kicker">Website Resmi</span>
                <h1 class="mt-6 max-w-2xl text-4xl font-extrabold leading-tight text-slate-900 sm:text-5xl">Pramuka USU</h1>
                <p class="mt-6 max-w-xl text-base leading-7 text-slate-600 sm:text-lg">Wadah pembinaan karakter, kepemimpinan, dan pengabdian bagi civitas Universitas Sumatera Utara melalui kegiatan Pramuka yang modern dan inklusif.</p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('news.index') }}" class="btn-primary">Lihat Berita</a>
                    <a href="{{ route('about') }}" class="btn-secondary">Tentang Kami</a>
                </div>
                <div class="mt-10 grid grid-cols-2 gap-4 sm:grid-cols-3">
                    @foreach (['Karakter', 'Kepemimpinan', 'Pengabdian'] as $value)
                        <div class="surface-card p-4 text-sm font-medium text-slate-700">{{ $value }}</div>
                    @endforeach
                </div>
            </div>

            <div class="surface-card p-6">
                <div class="text-sm font-semibold uppercase tracking-[0.2em] text-[#5D4037]">Berita Terbaru</div>
                <div id="hero-thumbnail-slider" class="mt-6 overflow-hidden rounded-3xl bg-slate-200">
                    @if ($latestNews->isNotEmpty())
                        <div class="relative aspect-[16/9]">
                            @foreach ($latestNews as $index => $post)
                                <a href="{{ route('news.show', $post->slug) }}" class="slide absolute inset-0 transition-opacity duration-700 ease-in-out @if ($index !== 0) opacity-0 @endif" data-slide="{{ $index }}">
                                    <img src="{{ $post->thumbnail_url }}" alt="{{ $post->title }}" class="h-full w-full object-cover" />
                                    <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/70 to-transparent px-4 py-4">
                                        <p class="text-xs uppercase tracking-[0.2em] text-white/80">{{ $post->category?->name ?? 'Berita' }}</p>
                                        <h3 class="mt-2 text-lg font-bold text-white line-clamp-2">{{ $post->title }}</h3>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="aspect-[16/9] flex items-center justify-center px-6 text-sm text-slate-600">Berita belum ada dipublikasi.</div>
                    @endif
                </div>

                @if ($latestNews->isNotEmpty())
                    <div id="hero-thumbnail-dots" class="mt-4 flex justify-center gap-2">
                        @foreach ($latestNews as $index => $post)
                            <button type="button" class="hero-dot h-2.5 w-2.5 rounded-full bg-slate-300 transition hover:bg-[#5D4037] @if ($index === 0) bg-[#5D4037] @endif" data-dot="{{ $index }}" aria-label="Slide {{ $index + 1 }}"></button>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const slides = Array.from(document.querySelectorAll('#hero-thumbnail-slider .slide'));
            const dots = Array.from(document.querySelectorAll('#hero-thumbnail-dots [data-dot]'));
            if (!slides.length) {
                return;
            }

            let currentIndex = 0;
            let slideTimer;

            const showSlide = function (index) {
                slides.forEach(function (slide, i) {
                    const active = i === index;
                    slide.classList.toggle('opacity-0', !active);
                    slide.classList.toggle('opacity-100', active);
                });

                dots.forEach(function (dot, i) {
                    dot.classList.toggle('bg-[#5D4037]', i === index);
                    dot.classList.toggle('bg-slate-300', i !== index);
                });
            };

            const startTimer = function () {
                slideTimer = setInterval(function () {
                    currentIndex = (currentIndex + 1) % slides.length;
                    showSlide(currentIndex);
                }, 3000);
            };

            dots.forEach(function (dot) {
                dot.addEventListener('click', function () {
                    currentIndex = Number(dot.dataset.dot);
                    showSlide(currentIndex);
                    clearInterval(slideTimer);
                    startTimer();
                });
            });

            showSlide(currentIndex);
            startTimer();
        });
    </script>
    </section>

    <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid gap-6 md:grid-cols-3">
            @foreach ([['label' => 'Jumlah User', 'value' => $stats['users']], ['label' => 'Berita Publik', 'value' => $stats['news']], ['label' => 'Agenda Aktif', 'value' => $stats['agendas']]] as $stat)
                <div class="surface-card p-6">
                    <div class="text-sm text-slate-500">{{ $stat['label'] }}</div>
                    <div class="mt-2 text-3xl font-extrabold text-[#5D4037]">{{ number_format($stat['value']) }}</div>
                </div>
            @endforeach
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

    <section class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
        <div>
            <span class="section-kicker">Agenda Terdekat</span>
            <h2 class="mt-4 text-3xl font-bold text-slate-900">Kegiatan berikutnya yang perlu Anda ikuti</h2>
        </div>

        <div class="mt-8 grid gap-6 md:grid-cols-3">
            @forelse ($upcomingAgendas as $agenda)
                <article class="surface-card p-6">
                    <div class="text-xs font-semibold uppercase tracking-[0.2em] text-[#5D4037]">{{ $agenda->status }}</div>
                    <h3 class="mt-3 text-xl font-bold text-slate-900">{{ $agenda->name }}</h3>
                    <p class="mt-3 text-sm text-slate-600">{{ $agenda->location }}</p>
                    <p class="mt-2 text-sm text-slate-500">{{ $agenda->starts_at?->format('d M Y, H:i') }}</p>
                </article>
            @empty
                <div class="surface-card p-6 text-sm text-slate-600">Belum ada agenda yang dipublikasikan.</div>
            @endforelse
        </div>
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