@extends('layouts.public')

@section('content')
    <section class="mx-auto max-w-4xl px-4 py-16 sm:px-6 lg:px-8 lg:py-24">
        <div class="surface-card p-8 sm:p-10">
            <span class="section-kicker">Kontak</span>
            <h1 class="mt-5 text-4xl font-extrabold text-slate-900">Hubungi Pramuka USU</h1>
            <p class="mt-4 text-lg leading-8 text-slate-600">Gunakan informasi berikut untuk menjangkau pengurus atau mengikuti kegiatan organisasi.</p>

            <div class="mt-8 grid gap-4 sm:grid-cols-2">
                <div class="rounded-xl bg-[#F5F5DC]/40 p-5">
                    <div class="text-sm font-semibold text-[#5D4037]">Email</div>
                    <div class="mt-2 text-slate-700">{{ $contactEmail }}</div>
                </div>
                <div class="rounded-xl bg-[#F5F5DC]/40 p-5">
                    <div class="text-sm font-semibold text-[#5D4037]">Telepon</div>
                    <div class="mt-2 text-slate-700">{{ $contactPhone }}</div>
                </div>
            </div>
        </div>
    </section>
@endsection