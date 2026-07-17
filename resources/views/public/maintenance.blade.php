@extends('layouts.public')

@section('content')
    <section class="mx-auto max-w-4xl px-4 py-24 text-center sm:px-6 lg:px-8">
        <div class="surface-card rounded-4xl border border-[#5D4037]/10 bg-white/95 p-12 shadow-xl">
            <span class="text-sm font-semibold uppercase tracking-[0.24em] text-[#5D4037]">Pemeliharaan</span>
            <h1 class="mt-6 text-4xl font-extrabold text-slate-900">Website sedang dalam pemeliharaan</h1>
            <p class="mx-auto mt-4 max-w-2xl text-lg leading-8 text-slate-600">Maaf, sementara ini akses ditutup untuk pengguna. Silakan coba kembali nanti.</p>
            <div class="mt-8 flex justify-center gap-3">
                <a href="{{ route('home') }}" class="rounded-full bg-[#5D4037] px-6 py-3 text-sm font-semibold text-white transition hover:bg-[#452a13]">Kembali ke Beranda</a>
            </div>
        </div>
    </section>
@endsection
