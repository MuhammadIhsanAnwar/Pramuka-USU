@extends('layouts.public')

@section('content')
    <section class="mx-auto max-w-4xl px-4 py-16 sm:px-6 lg:px-8 lg:py-24">
        <article class="surface-card overflow-hidden">
            <img src="{{ $newsPost->thumbnail_url }}" alt="{{ $newsPost->title }}" class="h-72 w-full object-cover">
            <div class="p-8 sm:p-10">
                <div class="text-xs font-semibold uppercase tracking-[0.2em] text-[#5D4037]">{{ $newsPost->category?->name ?? 'Berita' }}</div>
                <h1 class="mt-4 text-4xl font-extrabold text-slate-900">{{ $newsPost->title }}</h1>
                <div class="mt-4 text-sm text-slate-500">{{ $newsPost->author?->name }} · {{ $newsPost->published_at?->format('d M Y') }} · {{ number_format($newsPost->viewer_count) }} views</div>
                <div class="prose mt-8 max-w-none prose-slate">
                    {!! $newsPost->content !!}
                </div>
            </div>
        </article>

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