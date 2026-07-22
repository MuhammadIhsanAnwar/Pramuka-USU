<x-filament-panels::page>
    <div class="space-y-6 rounded-[24px] border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-slate-900">Kirim Berita</h2>
                <p class="mt-2 text-sm text-slate-600">Pilih tindakan: kirim berita baru atau lihat berita yang sudah Anda kirim.</p>
            </div>
        </div>

        <div class="mt-4 flex flex-wrap gap-3">
            <a href="{{ url('/user/berita-kirim') }}" class="rounded-full bg-[#3E271A] px-4 py-2 text-sm font-semibold text-white">Kirim Berita</a>
            <a href="{{ url('/user/berita-terkirim') }}" class="rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700">Berita Terkirim</a>
        </div>

        <div class="mt-6 text-sm text-slate-600">
            Pilih salah satu aksi untuk melanjutkan.
        </div>
    </div>
</x-filament-panels::page>
