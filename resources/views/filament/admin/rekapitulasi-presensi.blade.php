<div class="space-y-6">
    <h2 class="text-2xl font-semibold">Rekapitulasi Presensi</h2>

    <div class="overflow-hidden rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full min-w-full text-left text-sm text-slate-700">
                <thead class="bg-slate-50 text-slate-700">
                    <tr>
                        <th class="px-4 py-3 text-xs font-semibold uppercase text-slate-500">Nama Kegiatan</th>
                        <th class="px-4 py-3 text-xs font-semibold uppercase text-slate-500">Tanggal</th>
                        <th class="px-4 py-3 text-xs font-semibold uppercase text-slate-500">Total Presensi</th>
                        <th class="px-4 py-3 text-xs font-semibold uppercase text-slate-500">Hadir</th>
                        <th class="px-4 py-3 text-xs font-semibold uppercase text-slate-500">Terlambat</th>
                        <th class="px-4 py-3 text-xs font-semibold uppercase text-slate-500">Izin</th>
                        <th class="px-4 py-3 text-xs font-semibold uppercase text-slate-500">Tidak Hadir</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @foreach ($agendas as $agenda)
                        <tr>
                            <td class="px-4 py-3">{{ $agenda['name'] }}</td>
                            <td class="px-4 py-3">{{ \Illuminate\Support\Carbon::parse($agenda['starts_at'])->format('d M Y H:i') ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $agenda['total_attendances'] ?? 0 }}</td>
                            <td class="px-4 py-3">{{ $agenda['hadir_count'] ?? 0 }}</td>
                            <td class="px-4 py-3">{{ $agenda['terlambat_count'] ?? 0 }}</td>
                            <td class="px-4 py-3">{{ $agenda['izin_count'] ?? 0 }}</td>
                            <td class="px-4 py-3">{{ $agenda['tidak_count'] ?? 0 }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
