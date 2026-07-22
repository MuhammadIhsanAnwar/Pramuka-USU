<x-filament-panels::page>
    <div class="space-y-4">
        <div class="rounded-[24px] border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900">Berita Terkirim</h2>
                    <p class="mt-2 text-sm text-slate-600">Lihat berita yang Anda kirim dan hapus jika masih berstatus draf.</p>
                </div>
                @php
                    $isSubmission = request()->is('user/berita-kirim') || request()->is('user/berita-kirim/*');
                    $isSent = request()->is('user/berita-terkirim') || request()->is('user/berita-terkirim/*');
                @endphp
                <div class="flex items-center gap-2">
                    <button type="button" onclick="location.href='{{ url('/user/berita-kirim') }}'"
                            class="rounded-full px-4 py-2 text-sm font-semibold transition focus:outline-none {{ $isSubmission ? 'bg-[#3E271A] text-white shadow-sm' : 'border border-slate-200 text-slate-700 hover:bg-slate-50' }}"
                            style="{{ $isSubmission ? 'background:#3E271A;color:#ffffff;border:0;padding:8px 16px;border-radius:9999px;' : 'background:#ffffff;color:#374151;border:1px solid #e5e7eb;padding:8px 16px;border-radius:9999px;' }}">
                        Kirim Berita
                    </button>

                    <button type="button" onclick="location.href='{{ url('/user/berita-terkirim') }}'"
                            class="rounded-full ml-2 px-4 py-2 text-sm font-semibold transition focus:outline-none {{ $isSent ? 'bg-[#3E271A] text-white shadow-sm' : 'border border-slate-200 text-slate-700 hover:bg-slate-50' }}"
                            style="{{ $isSent ? 'background:#3E271A;color:#ffffff;border:0;padding:8px 16px;border-radius:9999px;' : 'background:#ffffff;color:#374151;border:1px solid #e5e7eb;padding:8px 16px;border-radius:9999px;' }}">
                        Berita Terkirim
                    </button>
                </div>
            </div>
        </div>

        <div class="rounded-[24px] border border-slate-200 bg-white p-5 shadow-sm">
            {{ $this->table }}
        </div>
    </div>
</x-filament-panels::page>
