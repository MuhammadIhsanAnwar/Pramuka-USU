<div class="grid gap-6 lg:grid-cols-3">
    <div class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex items-center justify-between gap-3">
            <div class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Profil</div>
            <div class="rounded-full bg-[#F5F5DC] px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-[#3E271A]">User</div>
        </div>

        <div class="mt-5 flex flex-col gap-4 sm:flex-row sm:items-center">
            <div class="flex h-24 w-24 shrink-0 items-center justify-center overflow-hidden rounded-2xl border border-slate-200 bg-[#F5F5DC] shadow-inner">
                @if (!blank($user?->avatar_url))
                    <img src="{{ $user->avatar_url }}" alt="Foto profil {{ $user->name }}" class="h-full w-full object-cover" />
                @else
                    <span class="text-2xl font-bold text-[#3E271A]">{{ strtoupper(substr($user?->name ?? 'U', 0, 1)) }}</span>
                @endif
            </div>

            <div class="min-w-0">
                <div class="text-xl font-bold text-slate-900">{{ $user?->name }}</div>
                <div class="mt-1 text-sm text-slate-500">{{ $user?->email }}</div>
            </div>
        </div>

        <div class="mt-5 flex flex-wrap gap-2">
            <span class="rounded-full border border-[#3E271A]/10 bg-[#F5F5DC] px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-[#3E271A]">Golongan: {{ $user?->golongan ?: '-' }}</span>
            <span class="rounded-full border border-[#3E271A]/10 bg-[#F5F5DC] px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-[#3E271A]">Tingkatan: {{ $user?->tingkatan ?: '-' }}</span>
        </div>

        <div class="mt-6 space-y-3 text-sm text-slate-700">
            <div class="rounded-3xl bg-slate-50 p-4">
                <div class="text-xs uppercase tracking-[0.2em] text-slate-500">Jabatan</div>
                <div class="mt-1 font-semibold text-slate-900">{{ $user?->jabatan ?: '-' }}</div>
            </div>
            <div class="rounded-3xl bg-slate-50 p-4">
                <div class="text-xs uppercase tracking-[0.2em] text-slate-500">Satuan</div>
                <div class="mt-1 font-semibold text-slate-900">{{ $user?->satuan ?: '-' }}</div>
            </div>
            <div class="rounded-3xl bg-slate-50 p-4">
                <div class="text-xs uppercase tracking-[0.2em] text-slate-500">NTA</div>
                <div class="mt-1 font-semibold text-slate-900">{{ $user?->nta ?: '-' }}</div>
            </div>
        </div>
    </div>
</div>