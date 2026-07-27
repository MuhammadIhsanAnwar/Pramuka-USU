<div class="overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-[0_14px_36px_rgba(15,23,42,0.08)]">
    <div class="bg-[#3E271A] px-5 py-5 sm:px-6">
    </div>

    <div class="p-5 sm:p-6">
        <div class="flex flex-col gap-4 border-b border-slate-100 pb-6 sm:flex-row sm:items-center">
            <div class="flex h-20 w-20 shrink-0 items-center justify-center overflow-hidden rounded-2xl bg-[#F5F5DC] text-xl font-bold text-[#3E271A] ring-4 ring-[#F5F5DC]">
                @if (filled($user?->avatar_path))
                <img src="{{ $user->avatar_url }}" alt="Foto profil {{ $user->name }}" class="h-full w-full object-cover" />
                @else
                {{ strtoupper(substr($user?->name ?? 'U', 0, 1)) }}
                @endif
            </div>

            <div class="min-w-0 flex-1">
                <h3 class="truncate text-xl font-bold text-slate-900">{{ $user?->name }}</h3>
                <p class="mt-1 truncate text-sm text-slate-500">{{ $user?->email }}</p>
            </div>

            @if (filled($user?->qr_code_url))
            <div class="mt-6 grid gap-4 rounded-4xl border border-slate-200 bg-slate-50 p-2 shadow-sm sm:grid-cols-[1.4fr_90px] sm:items-center">
                <div class="mx-auto flex h-12 w-12 items-center justify-center overflow-hidden rounded-3xl bg-white p-1 shadow-inner">
                    <img src="{{ $user->qr_code_url }}" alt="QR Code profil {{ $user->name }}" class="h-full w-full object-contain" />
                </div>
            </div>
            @endif

            <dl class="mt-5 grid gap-x-6 gap-y-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <dt class="text-xs font-medium uppercase tracking-[0.14em] text-slate-400">Nomor Tanda Anggota</dt>
                    <dd class="mt-1 font-semibold text-slate-800">{{ $user?->nta ?: '-' }}</dd>
                </div>

                <div>
                    <dt class="text-xs font-medium uppercase tracking-[0.14em] text-slate-400">Jabatan</dt>
                    <dd class="mt-1 truncate text-sm font-semibold text-slate-800">{{ $user?->jabatan ?: '-' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium uppercase tracking-[0.14em] text-slate-400">Satuan</dt>
                    <dd class="mt-1 truncate text-sm font-semibold text-slate-800">{{ $user?->satuan ?: '-' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium uppercase tracking-[0.14em] text-slate-400">Golongan</dt>
                    <dd class="mt-1 truncate text-sm font-semibold text-slate-800">{{ $user?->golongan ?: '-' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium uppercase tracking-[0.14em] text-slate-400">Tingkatan</dt>
                    <dd class="mt-1 truncate text-sm font-semibold text-slate-800">{{ $user?->tingkatan ?: '-' }}</dd>
                </div>
            </dl>
        </div>
    </div>
</div>