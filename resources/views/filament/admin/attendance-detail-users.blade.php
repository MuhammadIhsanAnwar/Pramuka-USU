<div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
    @if ($eventAgenda && $users->isNotEmpty())
        <style>
            #attendance-detail-users-table th.sortable,
            #attendance-detail-users-table th.sortable *,
            #attendance-detail-users-table th.sortable span,
            #attendance-detail-users-table th.sortable [role="button"] {
                background: transparent !important;
                background-color: transparent !important;
                background-image: none !important;
                border: none !important;
                box-shadow: none !important;
            }

            #attendance-detail-users-table th.sortable span {
                display: inline-flex;
            }
        </style>
        <div class="mt-6 overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table id="attendance-detail-users-table" class="w-full min-w-full border-collapse text-left text-sm text-slate-700" data-update-url="{{ route('admin.attendance.update-status', $eventAgenda) }}">
                    <thead class="bg-slate-50 text-slate-700">
                        <tr>
                            <th class="w-28 px-4 py-3 border-b border-slate-200 text-left text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">QR Code</th>
                            <th class="w-28 px-4 py-3 border-b border-slate-200 text-left text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Foto</th>
                            <th class="sortable w-80 px-4 py-3 border-b border-slate-200 text-left text-xs font-semibold uppercase tracking-[0.24em] text-slate-500 cursor-pointer" data-sort="name" data-direction="asc">
                                <span class="inline-flex w-full items-center justify-start gap-1 text-slate-700 font-semibold uppercase tracking-[0.24em] text-xs hover:text-slate-900">
                                    <span>Nama</span>
                                    <span class="sort-arrow"></span>
                                </span>
                            </th>
                            <th class="sortable w-48 px-4 py-3 border-b border-slate-200 text-left text-xs font-semibold uppercase tracking-[0.24em] text-slate-500 cursor-pointer" data-sort="jenis_user" data-direction="asc">
                                <span class="inline-flex w-full items-center justify-start gap-1 text-slate-700 font-semibold uppercase tracking-[0.24em] text-xs hover:text-slate-900">
                                    <span>Jenis Pengguna</span>
                                    <span class="sort-arrow"></span>
                                </span>
                            </th>
                            <th class="sortable w-48 px-4 py-3 border-b border-slate-200 text-left text-xs font-semibold uppercase tracking-[0.24em] text-slate-500 cursor-pointer" data-sort="satuan" data-direction="asc">
                                <span class="inline-flex w-full items-center justify-start gap-1 text-slate-700 font-semibold uppercase tracking-[0.24em] text-xs hover:text-slate-900">
                                    <span>Satuan</span>
                                    <span class="sort-arrow"></span>
                                </span>
                            </th>
                            <th class="w-40 px-4 py-3 border-b border-slate-200 text-left text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">
                                <span class="text-slate-700 font-semibold uppercase tracking-[0.24em] text-xs">Status</span>
                            </th>
                            <th class="w-48 px-4 py-3 border-b border-slate-200 text-left text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">
                                <span class="text-slate-700 font-semibold uppercase tracking-[0.24em] text-xs">Jam Presensi</span>
                            </th>
                            <th class="w-40 px-4 py-3 border-b border-slate-200 text-left text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">
                                <span class="text-slate-700 font-semibold uppercase tracking-[0.24em] text-xs">Metode</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @foreach ($users as $user)
                            @php
                                $attendance = $user->attendances->first();
                                $qrUrl = $user->qr_code_url ?? null;
                                $avatarUrl = $user->avatar_url;
                            @endphp
                            <tr class="hover:bg-slate-50" data-user-id="{{ $user->id }}" data-name="{{ $user->name }}" data-jenis-user="{{ $user->jenis_user_label }}" data-satuan="{{ $user->satuan }}">
                                <td class="px-4 py-4 align-middle border-b border-slate-200 whitespace-nowrap">
                                    @if ($qrUrl)
                                        <img src="{{ $qrUrl }}" alt="QR {{ $user->name }}" style="height:48px;width:48px;object-fit:cover;border-radius:12px;border:1px solid #cbd5e1;" />
                                    @else
                                        <span class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100 text-xs font-medium text-slate-500">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 align-middle border-b border-slate-200 whitespace-nowrap">
                                    <img src="{{ $avatarUrl }}" alt="Foto {{ $user->name }}" style="height:72px;width:54px;object-fit:cover;border-radius:8px;border:1px solid #cbd5e1;" />
                                </td>
                                <td class="px-4 py-4 align-middle border-b border-slate-200">
                                    <div class="text-sm font-semibold text-slate-900">{{ $user->name }}</div>
                                </td>
                                <td class="px-4 py-4 align-middle border-b border-slate-200 whitespace-nowrap text-slate-700">{{ $user->jenis_user_label }}</td>
                                <td class="px-4 py-4 align-middle border-b border-slate-200 whitespace-nowrap text-slate-700">{{ $user->satuan }}</td>
                                <td class="status-cell px-4 py-4 align-middle border-b border-slate-200 whitespace-nowrap">
                                    <div class="relative">
                                        @php
                                            $selectedStatus = 'tidak';
                                            if ($attendance && in_array($attendance->status, ['hadir', 'terlambat', 'izin'], true)) {
                                                $selectedStatus = 'hadir';
                                            }
                                        @endphp

                                        <select
                                            class="attendance-status-select w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm outline-none transition duration-150 focus:border-primary-500 focus:ring-2 focus:ring-primary-100"
                                            data-user-id="{{ $user->id }}"
                                            data-event-agenda-id="{{ $eventAgenda->id }}"
                                        >
                                            <option value="hadir" {{ $selectedStatus === 'hadir' ? 'selected' : '' }}>Hadir</option>
                                            <option value="tidak" {{ $selectedStatus === 'tidak' ? 'selected' : '' }}>Tidak Hadir</option>
                                        </select>
                                    </div>
                                </td>
                                <td class="scanned-cell px-4 py-4 align-middle border-b border-slate-200 whitespace-nowrap text-slate-700">{{ $attendance?->scanned_at?->format('d M Y H:i') ?? '-' }}</td>
                                <td class="method-cell px-4 py-4 align-middle border-b border-slate-200 whitespace-nowrap text-slate-700">{{ $attendance?->method ? ucfirst($attendance->method) : '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="mt-6 rounded-3xl border border-slate-200 bg-slate-50 p-6 text-sm text-slate-700">
            Belum ada peserta aktif untuk agenda ini atau data belum tersedia.
        </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const table = document.getElementById('attendance-detail-users-table');
        if (!table) {
            return;
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const updateUrl = table.dataset.updateUrl;
        const tbody = table.querySelector('tbody');

        function getSortValue(row, key) {
            const value = row.dataset[key];
            if (value !== undefined) {
                return value.toString().trim().toLowerCase();
            }
            const dashedKey = key.replace(/_/g, '-');
            return (row.getAttribute(`data-${dashedKey}`) ?? '').toString().trim().toLowerCase();
        }

        function sortRows(key, direction) {
            const rows = Array.from(tbody.querySelectorAll('tr[data-user-id]'));
            rows.sort((a, b) => {
                const aValue = getSortValue(a, key);
                const bValue = getSortValue(b, key);
                if (aValue === bValue) return 0;
                return direction === 'asc' ? aValue.localeCompare(bValue) : bValue.localeCompare(aValue);
            });
            rows.forEach(row => tbody.appendChild(row));
        }

        table.querySelectorAll('th.sortable').forEach(header => {
            const button = header.querySelector('button');
            const arrow = header.querySelector('.sort-arrow');
            header.addEventListener('click', () => {
                const sortKey = header.dataset.sort;
                const currentDirection = header.dataset.direction === 'asc' ? 'desc' : 'asc';
                header.dataset.direction = currentDirection;

                table.querySelectorAll('th.sortable').forEach(h => {
                    const arrowSpan = h.querySelector('.sort-arrow');
                    if (h !== header && arrowSpan) {
                        arrowSpan.textContent = '';
                    }
                });

                if (arrow) {
                    arrow.textContent = currentDirection === 'asc' ? '▲' : '▼';
                }

                sortRows(sortKey, currentDirection);
            });
        });

        async function updateStatus(userId, status, selectElement) {
            if (!csrfToken || !updateUrl) {
                return;
            }

            const response = await fetch(updateUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ user_id: userId, status }),
            });

            if (!response.ok) {
                console.error('Update status failed', response.statusText);
                return;
            }

            const data = await response.json();
            if (!data.success) {
                console.error('Update status failed', data);
                return;
            }

            const row = selectElement.closest('tr');
            if (!row) {
                return;
            }

            const statusCell = row.querySelector('.status-cell');
            const scannedCell = row.querySelector('.scanned-cell');
            const methodCell = row.querySelector('.method-cell');

            if (statusCell) {
                statusCell.textContent = data.statusLabel;
            }
            if (scannedCell) {
                scannedCell.textContent = data.scannedAt;
            }
            if (methodCell) {
                methodCell.textContent = data.method;
            }
        }

        tbody.querySelectorAll('select.attendance-status-select').forEach(select => {
            select.addEventListener('change', function () {
                const userId = this.dataset.userId;
                const status = this.value;
                updateStatus(userId, status, this);
            });
        });
    });
</script>
