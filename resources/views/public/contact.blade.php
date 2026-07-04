@extends('layouts.public')

@section('content')
    <section class="mx-auto max-w-4xl px-4 py-16 sm:px-6 lg:px-8 lg:py-24">
        <div class="surface-card p-8 sm:p-10">
            <span class="section-kicker">Kontak</span>
            <h1 class="mt-5 text-4xl font-extrabold text-slate-900">Hubungi Pramuka USU</h1>
            <p class="mt-4 text-lg leading-8 text-slate-600">Gunakan informasi berikut untuk menjangkau pengurus atau mengikuti kegiatan organisasi.</p>

            <div class="mt-8 rounded-xl bg-[#F5F5DC]/40 p-5">
                <div class="text-sm font-semibold text-[#5D4037]">Email</div>
                <div class="mt-2 text-slate-700">{{ $contactEmail }}</div>
            </div>

            <div class="mt-8 rounded-xl bg-[#F5F5DC]/40 p-5">
                <div class="text-sm font-semibold text-[#5D4037]">Ikuti Kami</div>
                <div class="mt-4 space-y-3 text-sm text-slate-700">
                    <a href="https://linktr.ee/PramukaUSU" target="_blank" rel="noopener" class="flex items-center gap-3 rounded-xl px-3 py-3 transition hover:bg-[#5D4037]/10 hover:text-[#5D4037]">
                        <img src="{{ asset('storage/ikon/Linktree copy.png') }}" alt="Linktree" class="h-5 w-5 object-contain" />
                        <span>Linktree</span>
                    </a>
                    <a href="https://instagram.com/pramuka_usu" target="_blank" rel="noopener" class="flex items-center gap-3 rounded-xl px-3 py-3 transition hover:bg-[#5D4037]/10 hover:text-[#5D4037]">
                        <img src="{{ asset('storage/ikon/Instagram copy.png') }}" alt="Instagram" class="h-5 w-5 object-contain" />
                        <span>Instagram</span>
                    </a>
                    <a href="https://facebook.com/pramukausu1974" target="_blank" rel="noopener" class="flex items-center gap-3 rounded-xl px-3 py-3 transition hover:bg-[#5D4037]/10 hover:text-[#5D4037]">
                        <img src="{{ asset('storage/ikon/Facebook copy.png') }}" alt="Facebook" class="h-5 w-5 object-contain" />
                        <span>Facebook</span>
                    </a>
                    <a href="https://threads.com/@pramuka_usu" target="_blank" rel="noopener" class="flex items-center gap-3 rounded-xl px-3 py-3 transition hover:bg-[#5D4037]/10 hover:text-[#5D4037]">
                        <img src="{{ asset('storage/ikon/Threads copy.png') }}" alt="Threads" class="h-5 w-5 object-contain" />
                        <span>Threads</span>
                    </a>
                    <a href="https://tiktok.com/@pramuka_usu" target="_blank" rel="noopener" class="flex items-center gap-3 rounded-xl px-3 py-3 transition hover:bg-[#5D4037]/10 hover:text-[#5D4037]">
                        <img src="{{ asset('storage/ikon/Tiktok copy.png') }}" alt="TikTok" class="h-5 w-5 object-contain" />
                        <span>TikTok</span>
                    </a>
                    <a href="https://youtube.com/@pramuka_usu" target="_blank" rel="noopener" class="flex items-center gap-3 rounded-xl px-3 py-3 transition hover:bg-[#5D4037]/10 hover:text-[#5D4037]">
                        <img src="{{ asset('storage/ikon/Youtube copy.png') }}" alt="YouTube" class="h-5 w-5 object-contain" />
                        <span>YouTube</span>
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection