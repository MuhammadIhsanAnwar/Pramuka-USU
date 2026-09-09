<div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
    @if ($session && $users->isNotEmpty())
        <div class="overflow-x-auto">
            <table class="w-full min-w-full border-collapse text-left text-sm text-slate-700">
                <thead class="bg-slate-50 text-slate-700">
                    <tr>
                        <th class="px-4 py-3 border-b border-slate-200 text-left text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Nama</th>
                        <th class="px-4 py-3 border-b border-slate-200 text-left text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Jenis</th>
                        <th class="px-4 py-3 border-b border-slate-200 text-left text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Satuan</th>
                        <th class="px-4 py-3 border-b border-slate-200 text-left text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Status</th>
                        <th class="px-4 py-3 border-b border-slate-200 text-left text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Jam Presensi</th>
                        <th class="px-4 py-3 border-b border-slate-200 text-left text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Metode</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @foreach ($users as $user)
                        @php
                            $record = $user->presensiRecords->first();
                        @endphp
                        <tr>
                            <td class="px-4 py-4 border-b border-slate-200">{{ $user->name }}</td>
                            <td class="px-4 py-4 border-b border-slate-200">{{ $user->jenis_user_label }}</td>
                            <td class="px-4 py-4 border-b border-slate-200">{{ $user->satuan }}</td>
                            <td class="px-4 py-4 border-b border-slate-200">
                                <select class="attendance-status-select w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700" data-user-id="{{ $user->id }}" data-session-id="{{ $session->id }}">
                                    <option value="hadir" @selected($record?->status === 'hadir')>Hadir</option>
                                    <option value="tidak" @selected($record?->status === 'tidak')>Tidak Hadir</option>
                                </select>
                            </td>
                            <td class="px-4 py-4 border-b border-slate-200">{{ $record?->scanned_at?->format('d M Y H:i') ?? '-' }}</td>
                            <td class="px-4 py-4 border-b border-slate-200">{{ $record?->method ? ucfirst($record->method) : '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-sm text-slate-700">Belum ada peserta aktif untuk sesi presensi ini.</div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('select.attendance-status-select').forEach(function (select) {
            select.addEventListener('change', async function () {
                const sessionId = this.dataset.sessionId;
                const userId = this.dataset.userId;
                const status = this.value;
                const token = document.querySelector('meta[name="csrf-token"]').content;

                await fetch('{{ route('admin.presensi.update-status', $session) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json',
                    },
                    body: new URLSearchParams({ user_id: userId, status }),
                });
            });
        });
    });
</script>
