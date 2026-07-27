@extends('layouts.public')

@section('content')
    <section class="mx-auto max-w-5xl px-4 py-20 sm:px-6 lg:px-8">
        <div class="rounded-4xl border border-slate-200 bg-white shadow-[0_30px_70px_rgba(15,23,42,0.08)] p-8 sm:p-10">
            <div class="grid gap-10 lg:grid-cols-[280px_auto] lg:items-start">
                <div class="space-y-5 rounded-[28px] border border-slate-200 bg-slate-50 p-6 text-center">
                    <div class="mx-auto flex h-40 w-40 items-center justify-center overflow-hidden rounded-[28px] bg-white shadow-sm">
                        @if (filled($member->avatar_path))
                            <img src="{{ $member->avatar_url }}" alt="Foto {{ $member->name }}" class="h-full w-full object-cover" />
                        @else
                            <span class="text-5xl font-semibold text-slate-600">{{ strtoupper(substr($member->name, 0, 1)) }}</span>
                        @endif
                    </div>

                    <div class="space-y-1">
                        <h1 class="text-2xl font-semibold text-slate-900">{{ $member->name }}</h1>
                        <p class="text-sm uppercase tracking-[0.18em] text-slate-500">{{ $member->satuan ?? 'Anggota' }}</p>
                    </div>

                    @if (filled($member->qr_code_url))
                        <div class="mt-6 rounded-4xl border border-slate-200 bg-white p-5 shadow-sm">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">QR Code Profil</p>
                            <div class="mx-auto mt-4 inline-flex h-24 w-24 items-center justify-center overflow-hidden rounded-3xl bg-slate-50 p-2">
                                <img src="{{ $member->qr_code_url }}" alt="QR Code profil {{ $member->name }}" class="h-full w-full object-contain" />
                            </div>
                            <p class="mt-3 text-center text-sm text-slate-600">Scan atau simpan QR ini untuk membuka profil publik anggota kapan saja.</p>
                        </div>
                    @else
                        <div class="mt-6 rounded-4xl border border-slate-200 bg-white p-6 text-center shadow-sm">
                            <p class="text-sm font-medium text-slate-700">QR Code belum tersedia.</p>
                            <p class="mt-2 text-xs text-slate-500">Silakan hubungi admin untuk mengaktifkan QR Code.</p>
                        </div>
                    @endif
                </div>

                <div class="space-y-8">
                    <div class="grid gap-6 rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm sm:grid-cols-2">
                        <div class="space-y-2">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Nomor Tanda Anggota</p>
                            <p class="text-base font-semibold text-slate-900">{{ $member->nta ?? '-' }}</p>
                        </div>

                        <div class="space-y-2">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Jabatan</p>
                            <p class="text-base font-semibold text-slate-900">{{ $member->jabatan ?? '-' }}</p>
                        </div>

                        <div class="space-y-2">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Satuan</p>
                            <p class="text-base font-semibold text-slate-900">{{ $member->satuan ?? '-' }}</p>
                        </div>

                        <div class="space-y-2">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Golongan</p>
                            <p class="text-base font-semibold text-slate-900">{{ $member->golongan ?? '-' }}</p>
                        </div>

                        <div class="space-y-2 sm:col-span-2">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Tingkatan</p>
                            <p class="text-base font-semibold text-slate-900">{{ $member->tingkatan ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="rounded-4xl border border-slate-200 bg-slate-50 p-6">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Tautan Profil</p>
                        <p class="mt-2 text-sm text-slate-700">Salin tautan ini untuk membagikan halaman profil anggota.</p>
                        <p class="mt-4 text-xs uppercase tracking-[0.18em] text-slate-400">URL Unik</p>
                        <p class="break-all text-sm font-medium text-slate-900">{{ route('member.show', ['uuid' => $member->uuid]) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
