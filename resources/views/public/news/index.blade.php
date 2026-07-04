@extends('layouts.public')

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-24">
        <div>
            <span class="section-kicker">Berita</span>
            <h1 class="mt-5 text-4xl font-extrabold text-slate-900">Publikasi Pramuka USU</h1>
        </div>

        <div class="mt-8 flex flex-wrap gap-3">
            <a href="{{ route('news.index') }}" class="rounded-full border px-4 py-2 text-sm font-semibold transition {{ blank($selectedCategory) ? 'border-[#5D4037] bg-[#5D4037] text-white' : 'border-[#5D4037]/15 bg-white text-slate-700 hover:border-[#5D4037]/30 hover:text-[#5D4037]' }}">Semua</a>
            @foreach ($categories as $category)
                <a href="{{ route('news.index', ['kategori' => $category->slug]) }}" class="rounded-full border px-4 py-2 text-sm font-semibold transition {{ $selectedCategory === $category->slug ? 'border-[#5D4037] bg-[#5D4037] text-white' : 'border-[#5D4037]/15 bg-white text-slate-700 hover:border-[#5D4037]/30 hover:text-[#5D4037]' }}">{{ $category->name }}</a>
            @endforeach
        </div>

        <div class="mt-10 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($newsPosts as $post)
                <article class="surface-card overflow-hidden">
                    <img src="{{ $post->thumbnail_url }}" alt="{{ $post->title }}" class="h-56 w-full object-cover">
                    <div class="p-6">
                        <div class="text-xs font-semibold uppercase tracking-[0.2em] text-[#5D4037]">{{ $post->category?->name ?? 'Berita' }}</div>
                        <h2 class="mt-3 text-lg font-bold text-slate-900">{{ $post->title }}</h2>
                        <p class="mt-3 text-sm leading-6 text-slate-600">{{ $post->excerpt }}</p>
                        <div class="mt-5 flex items-center justify-between text-xs text-slate-500">
                            <span>{{ $post->published_at?->format('d M Y') }}</span>
                            <a href="{{ route('news.show', $post->slug) }}" class="font-semibold text-[#5D4037]">Baca</a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="surface-card p-6 text-sm text-slate-600">Tidak ada berita yang cocok dengan filter ini.</div>
            @endforelse
        </div>

        <div class="mt-10">
            {{ $newsPosts->links() }}
        </div>
    </section>
@endsection