@extends('layouts.public')

@section('content')
    <section class="mx-auto max-w-4xl px-4 py-16 sm:px-6 lg:px-8 lg:py-24">
        <div class="surface-card p-8 sm:p-10">
            <span class="section-kicker">{{ $title }}</span>
            <h1 class="mt-6 text-4xl font-extrabold text-slate-900">{{ $title }}</h1>
            <p class="mt-5 text-lg leading-8 text-slate-600">{{ $lead }}</p>

            <div class="mt-8 space-y-4">
                @foreach ($points as $point)
                    <div class="rounded-xl bg-[#F5F5DC]/40 px-4 py-4 text-slate-700">{{ $point }}</div>
                @endforeach
            </div>
        </div>
    </section>
@endsection