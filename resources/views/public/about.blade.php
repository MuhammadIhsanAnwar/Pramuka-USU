@extends('layouts.public')

@section('content')
<section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-24">
    <div class="surface-card overflow-hidden p-8 sm:p-10">
        <div class="grid gap-8 lg:grid-cols-[minmax(320px,1fr)_280px] lg:items-start">
            <div>
                <span class="section-kicker">Tentang Kami</span>
                <h1 class="mt-4 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">Pramuka USU</h1>
                <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-600">Organisasi pembinaan karakter, kepemimpinan, dan pelayanan yang menguatkan mahasiswa Universitas Sumatera Utara melalui kegiatan pramuka yang inspiratif, inklusif, dan berdampak.</p>
                <div class="mt-8 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-3xl bg-[#F5F5DC] p-6 shadow-sm">
                        <div class="text-sm font-semibold uppercase text-[#5D4037]">Misi</div>
                        <p class="mt-3 text-sm leading-6 text-slate-700">Membina kader berjiwa luhur, disiplin, mandiri, dan berbakti kepada masyarakat.</p>
                    </div>
                    <div class="rounded-3xl bg-[#F5F5DC] p-6 shadow-sm">
                        <div class="text-sm font-semibold uppercase text-[#5D4037]">Visi</div>
                        <p class="mt-3 text-sm leading-6 text-slate-700">Menjadi wadah Pramuka kampus yang unggul, beretika, dan berpengaruh dalam pembangunan karakter bangsa.</p>
                    </div>
                </div>
            </div>

            <aside class="rounded-4xl bg-[#5D4037] p-8 text-white shadow-xl">
                <div class="text-sm font-semibold uppercase tracking-[0.24em] text-[#F7E4A7]">SIPRAUSU</div>
                <h2 class="mt-6 text-2xl font-bold">Sistem Informasi Pramuka USU</h2>
                <p class="mt-4 text-sm leading-6 text-[#F4E5C0]">Portal resmi untuk berita, agenda, galeri, dan profil organisasi Pramuka USU yang dikelola secara digital oleh tim admin.</p>
                <div class="mt-8 space-y-4 text-sm text-[#F4E5C0]">
                    <div class="flex items-start gap-3">
                        <span class="mt-1 inline-flex h-8 w-8 items-center justify-center rounded-2xl bg-white/10 text-sm font-bold text-[#5D4037]">1</span>
                        <span>Transparansi data organisasi dan kegiatan.</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="mt-1 inline-flex h-8 w-8 items-center justify-center rounded-2xl bg-white/10 text-sm font-bold text-[#5D4037]">2</span>
                        <span>Pengelolaan konten admin sederhana dan rapi.</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="mt-1 inline-flex h-8 w-8 items-center justify-center rounded-2xl bg-white/10 text-sm font-bold text-[#5D4037]">3</span>
                        <span>Dukungan profil tim majelis, racana, dan ambalan.</span>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 pb-20 sm:px-6 lg:px-8">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <span class="section-kicker">Struktur Tim</span>
            <h2 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-900">Tim Pengurus dan Pembina</h2>
        </div>
        <div class="text-sm text-slate-500">Pilih tab untuk melihat masing-masing kelompok tim.</div>
    </div>

    <div class="mt-8 overflow-hidden rounded-4xl border border-[#5D4037]/10 bg-white shadow-[0_24px_80px_rgba(93,64,55,0.08)]">
        <div class="space-y-1 border-b border-[#5D4037]/10 bg-[#F5F5DC]/80 px-4 py-4 sm:px-6">
            <nav class="flex flex-wrap gap-3" aria-label="Grup tim">
                @foreach ($groups as $group)
                    <button type="button" class="group-tab inline-flex items-center rounded-full border border-transparent bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-[#5D4037]/30 hover:bg-[#F5F5DC]" data-group="group-{{ $group->slug }}">{{ $group->name }}</button>
                @endforeach
            </nav>
        </div>

        @foreach ($groups as $group)
        <div class="group-panel hidden p-8" id="group-{{ $group->slug }}">
            <div class="grid gap-10 lg:grid-cols-[minmax(240px,280px)_1fr] lg:items-start">
                <div class="rounded-4xl bg-[#F9F4E5] p-8">
                    <div class="text-sm font-semibold uppercase tracking-[0.24em] text-[#5D4037]">{{ $group->name }}</div>
                    <p class="mt-4 text-sm leading-7 text-slate-700">{{ $group->description ?: 'Tim ini bertanggung jawab menjaga semangat, disiplin, dan koordinasi kegiatan Pramuka USU.' }}</p>
                    <div class="mt-8 grid gap-3">
                        @foreach ($group->members->take(3) as $member)
                        <div class="rounded-3xl bg-white p-4 shadow-sm">
                            <div class="text-sm font-bold text-[#5D4037]">{{ $member->position ?: 'Anggota' }}</div>
                            <div class="mt-2 text-base font-semibold text-slate-900">{{ $member->name }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="grid gap-6 lg:grid-cols-2">
                    @forelse ($group->members as $member)
                    <article class="overflow-hidden rounded-4xl border border-[#5D4037]/10 bg-white shadow-sm">
                        <img src="{{ $member->photo_url }}" alt="{{ $member->name }}" class="h-56 w-full object-cover">
                        <div class="p-6">
                            <div class="text-sm font-semibold uppercase tracking-[0.2em] text-[#5D4037]">{{ $member->position ?: 'Anggota' }}</div>
                            <h3 class="mt-3 text-xl font-bold text-slate-900">{{ $member->name }}</h3>
                            <p class="mt-3 text-sm leading-6 text-slate-600">{{ $member->bio ?: 'Deskripsi singkat tentang peran anggota ini belum tersedia.' }}</p>
                        </div>
                    </article>
                    @empty
                    <div class="rounded-4xl bg-[#F5F5DC] p-8 text-slate-700">Belum ada anggota tim yang aktif di grup ini.</div>
                    @endforelse
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tabs = document.querySelectorAll('.group-tab');
        const panels = document.querySelectorAll('.group-panel');

        function activateTab(targetId) {
            panels.forEach(panel => {
                const isActive = panel.id === targetId;
                panel.classList.toggle('hidden', !isActive);
            });

            tabs.forEach(tab => {
                const isSelected = tab.dataset.group === targetId;
                tab.classList.toggle('bg-[#5D4037]', isSelected);
                tab.classList.toggle('text-white', isSelected);
                tab.classList.toggle('border-[#5D4037]', isSelected);
                tab.classList.toggle('bg-white', !isSelected);
            });
        }

        if (tabs.length > 0) {
            const firstTab = tabs[0];
            activateTab(firstTab.dataset.group);

            tabs.forEach(tab => {
                tab.addEventListener('click', function () {
                    activateTab(this.dataset.group);
                });
            });
        }
    });
</script>
@endsection
