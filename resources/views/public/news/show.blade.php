@extends('layouts.public')

@section('content')
    <section class="mx-auto max-w-4xl px-4 py-16 sm:px-6 lg:px-8 lg:py-24">
        <article class="surface-card overflow-hidden">
            @php
                $imageUrls = collect([$newsPost->thumbnail_url])
                    ->merge(collect($newsPost->image_paths ?? [])->map(fn ($path): string => asset('storage/'.$path)))
                    ->filter()
                    ->unique()
                    ->values()
                    ->all();
            @endphp

            @if (count($imageUrls) > 1)
                <div class="relative overflow-hidden bg-slate-900">
                    <div id="news-gallery-track" class="flex transition-transform duration-500" style="width: {{ count($imageUrls) * 100 }}%;">
                        @foreach ($imageUrls as $imageUrl)
                            <img src="{{ $imageUrl }}" alt="{{ $newsPost->title }}" class="h-80 w-full flex-shrink-0 object-cover sm:h-96" style="width: {{ 100 / count($imageUrls) }}%;">
                        @endforeach
                    </div>

                    <button id="news-gallery-prev" type="button" class="absolute left-4 top-1/2 -translate-y-1/2 rounded-full bg-white/80 px-3 py-2 text-slate-900 shadow-lg transition hover:bg-white">
                        ‹
                    </button>
                    <button id="news-gallery-next" type="button" class="absolute right-4 top-1/2 -translate-y-1/2 rounded-full bg-white/80 px-3 py-2 text-slate-900 shadow-lg transition hover:bg-white">
                        ›
                    </button>

                    <div class="absolute bottom-4 left-1/2 flex -translate-x-1/2 gap-2">
                        @foreach ($imageUrls as $index => $imageUrl)
                            <button type="button" class="h-2.5 w-2.5 rounded-full bg-white/60" data-gallery-dot="{{ $index }}"></button>
                        @endforeach
                    </div>
                </div>
            @else
                <img src="{{ $imageUrls[0] ?? asset('images/news-placeholder.jpg') }}" alt="{{ $newsPost->title }}" class="h-80 w-full object-cover sm:h-96">
            @endif

            <div class="p-8 sm:p-10">
                <div class="text-xs font-semibold uppercase tracking-[0.2em] text-[#5D4037]">{{ $newsPost->category?->name ?? 'Berita' }}</div>
                <h1 class="mt-4 text-4xl font-extrabold text-slate-900">{{ $newsPost->title }}</h1>
                <div class="mt-4 text-sm text-slate-500">{{ $newsPost->author?->name }} · {{ $newsPost->published_at?->format('d M Y') }} · {{ number_format($newsPost->viewer_count) }} views</div>
                <div class="prose mt-8 max-w-none prose-slate">
                    {!! $newsPost->content !!}
                </div>
            </div>
        </article>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const track = document.getElementById('news-gallery-track');
                const prev = document.getElementById('news-gallery-prev');
                const next = document.getElementById('news-gallery-next');
                const dots = Array.from(document.querySelectorAll('[data-gallery-dot]'));
                let activeIndex = 0;

                function updateGallery() {
                    track.style.transform = `translateX(-${activeIndex * (100 / dots.length)}%)`;
                    dots.forEach((dot, index) => {
                        dot.classList.toggle('bg-white', index === activeIndex);
                        dot.classList.toggle('bg-white/60', index !== activeIndex);
                    });
                }

                prev?.addEventListener('click', function () {
                    activeIndex = (activeIndex - 1 + dots.length) % dots.length;
                    updateGallery();
                });

                next?.addEventListener('click', function () {
                    activeIndex = (activeIndex + 1) % dots.length;
                    updateGallery();
                });

                dots.forEach((dot) => {
                    dot.addEventListener('click', function () {
                        activeIndex = Number(this.dataset.galleryDot);
                        updateGallery();
                    });
                });

                updateGallery();
            });
        </script>

        @if ($relatedNews->isNotEmpty())
            <div class="mt-12">
                <span class="section-kicker">Berita Terkait</span>
                <div class="mt-6 grid gap-6 md:grid-cols-3">
                    @foreach ($relatedNews as $item)
                        <a href="{{ route('news.show', $item->slug) }}" class="surface-card p-5 transition hover:-translate-y-1 hover:shadow-xl">
                            <h2 class="text-lg font-bold text-slate-900">{{ $item->title }}</h2>
                            <p class="mt-3 text-sm text-slate-600">{{ $item->excerpt }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </section>
@endsection