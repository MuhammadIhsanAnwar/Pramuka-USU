<div class="overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-[0_14px_36px_rgba(15,23,42,0.08)]">
    <div class="bg-[#3E271A] px-5 py-5 sm:px-6">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-[#EDE4C9]">Dashboard User</p>
                <h2 class="mt-1 text-lg font-semibold text-white">Ringkasan Profil</h2>
            </div>

            <span class="rounded-full border border-white/20 bg-white/10 px-3 py-1.5 text-xs font-medium text-white">Profil Saya</span>
        </div>
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

            <span class="w-fit rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700">Akun aktif</span>
        </div>

        <dl class="mt-5 grid gap-x-6 gap-y-4 sm:grid-cols-2">
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
            <div class="sm:col-span-2">
                <dt class="text-xs font-medium uppercase tracking-[0.14em] text-slate-400">Nomor Tanda Anggota</dt>
                <dd class="mt-1 font-semibold text-slate-800">{{ $user?->nta ?: '-' }}</dd>
            </div>
        </dl>

        <div class="mt-6 grid grid-cols-3 divide-x divide-slate-200 rounded-2xl border border-slate-200 bg-slate-50 px-2 py-3">
            <div class="px-2 text-center">
                <div class="text-lg font-bold text-[#3E271A]">{{ $attendanceCount }}</div>
                <div class="mt-0.5 text-[10px] font-semibold uppercase tracking-[0.12em] text-slate-500">Presensi</div>
            </div>
            <div class="px-2 text-center">
                <div class="text-lg font-bold text-[#3E271A]">{{ $newsCount }}</div>
                <div class="mt-0.5 text-[10px] font-semibold uppercase tracking-[0.12em] text-slate-500">Berita</div>
            </div>
            <div class="px-2 text-center">
                <div class="text-lg font-bold text-[#3E271A]">{{ $upcomingAgendaCount }}</div>
                <div class="mt-0.5 text-[10px] font-semibold uppercase tracking-[0.12em] text-slate-500">Agenda</div>
            </div>
        </div>
    </div>
</div>