<div class="p-4">
    <h3 class="text-sm font-semibold mb-4">Lakukan Presensi</h3>

    <p class="text-sm text-slate-600 mb-4">Nama: <strong>{{ auth()->user()->name }}</strong></p>
    <p class="text-sm text-slate-600 mb-4">Agenda: <strong>{{ $eventAgenda->name }}</strong></p>

    <form id="presensi-gps-form">
        @csrf
        <input type="hidden" name="event_agenda_id" value="{{ $eventAgenda->id }}">
        <div class="mb-3">
            <label class="text-xs text-slate-500">Latitude</label>
            <input id="latitude" name="latitude" class="w-full rounded border px-3 py-2 text-sm" readonly>
        </div>
        <div class="mb-3">
            <label class="text-xs text-slate-500">Longitude</label>
            <input id="longitude" name="longitude" class="w-full rounded border px-3 py-2 text-sm" readonly>
        </div>

        <div class="flex gap-3 mt-4">
            <button type="button" id="btn-get-location" class="btn btn-primary">Ambil Lokasi</button>
            <button type="button" id="btn-submit-presensi" class="btn btn-secondary">Kirim Presensi</button>
        </div>

        <p id="presensi-message" class="text-sm mt-3"></p>
    </form>

    <script>
        (function () {
            const btnGet = document.getElementById('btn-get-location');
            const btnSubmit = document.getElementById('btn-submit-presensi');
            const latInput = document.getElementById('latitude');
            const lonInput = document.getElementById('longitude');
            const message = document.getElementById('presensi-message');

            btnGet.addEventListener('click', function () {
                if (!navigator.geolocation) {
                    message.textContent = 'Geolocation tidak didukung di browser ini.';
                    return;
                }
                message.textContent = 'Mencari lokasi...';
                navigator.geolocation.getCurrentPosition(function (pos) {
                    latInput.value = pos.coords.latitude.toFixed(7);
                    lonInput.value = pos.coords.longitude.toFixed(7);
                    message.textContent = 'Lokasi berhasil diambil.';
                }, function (err) {
                    message.textContent = 'Gagal mengambil lokasi: ' + (err.message || 'permission denied');
                }, { enableHighAccuracy: true, timeout: 10000 });
            });

            btnSubmit.addEventListener('click', function () {
                const form = document.getElementById('presensi-gps-form');
                const data = new FormData(form);

                message.textContent = 'Mengirim presensi...';

                fetch('{{ route('user.presensi.gps') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                    body: data,
                })
                .then(res => res.json())
                .then(json => {
                    if (json.success) {
                        message.textContent = json.message || 'Berhasil';
                        // reload page to reflect new presensi
                        setTimeout(() => window.location.reload(), 800);
                    } else {
                        message.textContent = json.message || 'Terjadi kesalahan';
                    }
                })
                .catch(err => {
                    message.textContent = 'Terjadi kesalahan saat mengirim presensi.';
                });
            });
        })();
    </script>
</div>