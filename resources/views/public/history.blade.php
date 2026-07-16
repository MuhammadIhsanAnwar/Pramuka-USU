@extends('layouts.public')

@section('content')
    <section class="mx-auto max-w-4xl px-4 py-16 sm:px-6 lg:px-8 lg:py-24">
        <div class="surface-card p-8 sm:p-10">
            <span class="section-kicker">{{ $title }}</span>
            <h1 class="mt-6 text-4xl font-extrabold text-slate-900">{{ $title }}</h1>
            <p class="mt-5 text-lg leading-8 text-slate-600">{{ $lead }}</p>
            <div class="mt-10 space-y-4">
                @if(! empty($photo_paths))
                    <div class="grid gap-4 sm:grid-cols-2">
                        @foreach($photo_paths as $photo)
                            <img src="{{ asset($photo) }}" alt="Foto Sejarah" class="h-64 w-full rounded-3xl object-cover shadow-sm" />
                        @endforeach
                    </div>
                @endif

                @if($content)
                    {!! $content !!}
                @else
                    <div class="rounded-xl bg-[#F5F5DC]/40 px-4 py-4 text-slate-700">
                        Konten Sejarah belum tersedia.
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
