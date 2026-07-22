<x-filament-panels::page>
    <div class="space-y-6 rounded-[24px] border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex items-center justify-between gap-3">
            <div>
                <h2 class="text-xl font-semibold text-slate-900">Kirim Berita</h2>
                <p class="mt-2 text-sm text-slate-600">Isi form berikut untuk mengirim berita ke admin.</p>
            </div>
            <div class="flex items-center gap-2">
                @php
                    $isSubmission = request()->is('user/berita-kirim') || request()->is('user/berita-kirim/*');
                    $isSent = request()->is('user/berita-terkirim') || request()->is('user/berita-terkirim/*');
                @endphp

                <button type="button" onclick="location.href='{{ url('/user/berita-kirim') }}'"
                        class="rounded-full px-4 py-2 text-sm font-semibold transition focus:outline-none {{ $isSubmission ? 'bg-[#3E271A] text-white shadow-sm' : 'border border-slate-200 text-slate-700 hover:bg-slate-50' }}"
                        style="{{ $isSubmission ? 'background:#3E271A;color:#ffffff;border:0;padding:8px 16px;border-radius:9999px;' : 'background:#ffffff;color:#374151;border:1px solid #e5e7eb;padding:8px 16px;border-radius:9999px;' }}">
                    Kirim Berita
                </button>

                <button type="button" onclick="location.href='{{ url('/user/berita-terkirim') }}'"
                        class="rounded-full px-4 py-2 text-sm font-semibold transition focus:outline-none {{ $isSent ? 'bg-[#3E271A] text-white shadow-sm' : 'border border-slate-200 text-slate-700 hover:bg-slate-50' }}"
                        style="{{ $isSent ? 'background:#3E271A;color:#ffffff;border:0;padding:8px 16px;border-radius:9999px;' : 'background:#ffffff;color:#374151;border:1px solid #e5e7eb;padding:8px 16px;border-radius:9999px;' }}">
                    Berita Terkirim
                </button>
            </div>

        <div class="rounded-[20px] border border-slate-200 bg-slate-50/70 p-4">
            <form wire:submit="submit" class="space-y-5">
                {{ $this->form }}

                <div class="flex items-center justify-end">
                    <button type="submit" class="rounded-full bg-[#3E271A] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#2f1d12]">
                        Kirim Berita
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-filament-panels::page>
