<div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 shadow-sm">
    <div class="flex items-center justify-between gap-4">
        <div>
            <h3 class="text-lg font-semibold text-slate-900">Titik Lokasi Presensi</h3>
            <p class="mt-1 text-sm text-slate-600">Klik pada peta untuk menetapkan latitude dan longitude presensi.</p>
        </div>
    </div>

    <div id="attendance-location-map" class="mt-4 h-72 w-full rounded-2xl border border-slate-300"></div>

    <div class="mt-4 grid gap-4 sm:grid-cols-2">
        <div>
            <label class="block text-sm font-medium text-slate-700" for="attendance-latitude">Latitude</label>
            <input id="attendance-latitude" name="latitude" type="text" readonly required class="mt-1 block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm" />
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700" for="attendance-longitude">Longitude</label>
            <input id="attendance-longitude" name="longitude" type="text" readonly required class="mt-1 block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm" />
        </div>
    </div>

    <div class="mt-4 flex flex-wrap items-center gap-3">
        <button id="attendance-current-location" type="button" class="rounded-2xl bg-slate-800 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">Gunakan Lokasi Perangkat</button>
        <p id="attendance-location-status" class="text-sm text-slate-600">Klik tombol untuk mengambil GPS dari perangkat Anda.</p>
    </div>
</div>

@push('scripts')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-sA+e2THo0XJTTQaUakHj2yP5Q0X0w/2RwV+6E1v7kDU=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-o9N1j2n1+cp231vPJv9CEwywzjpn3pWBXLE9m+dwYyM=" crossorigin=""></script>
    <script>
        window.attendanceLocationPickerInit = window.attendanceLocationPickerInit || function () {
            if (window.attendanceLocationPickerInitialized) {
                return;
            }

            const mapContainer = document.getElementById('attendance-location-map');
            const latitudeInput = document.getElementById('attendance-latitude');
            const longitudeInput = document.getElementById('attendance-longitude');
            const currentLocationButton = document.getElementById('attendance-current-location');
            const statusText = document.getElementById('attendance-location-status');

            if (!mapContainer || !latitudeInput || !longitudeInput) {
                return;
            }

            if (window.attendanceLocationPickerInitialized) {
                return;
            }

            window.attendanceLocationPickerInitialized = true;

            const initialLat = parseFloat(latitudeInput.value) || -0.5100;
            const initialLng = parseFloat(longitudeInput.value) || 98.6626;
            const initialZoom = 6;

            try {
                const map = L.map(mapContainer).setView([initialLat, initialLng], initialZoom);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap contributors',
                }).addTo(map);

                const marker = L.marker([initialLat, initialLng], {
                    draggable: true,
                }).addTo(map);

                const updateInputs = (lat, lng) => {
                    latitudeInput.value = lat.toFixed(7);
                    longitudeInput.value = lng.toFixed(7);
                };

                const setMarkerPosition = (lat, lng) => {
                    const position = L.latLng(lat, lng);
                    marker.setLatLng(position);
                    map.setView(position, 15);
                    updateInputs(lat, lng);
                };

                marker.on('dragend', (event) => {
                    const position = event.target.getLatLng();
                    updateInputs(position.lat, position.lng);
                });

                map.on('click', (event) => {
                    const position = event.latlng;
                    setMarkerPosition(position.lat, position.lng);
                });

                if (currentLocationButton) {
                    currentLocationButton.addEventListener('click', function () {
                        if (!navigator.geolocation) {
                            statusText.textContent = 'Geolocation tidak didukung di browser Anda.';
                            return;
                        }

                        statusText.textContent = 'Mencari lokasi...';

                        navigator.geolocation.getCurrentPosition(
                            function (position) {
                                setMarkerPosition(position.coords.latitude, position.coords.longitude);
                                statusText.textContent = 'Lokasi perangkat berhasil diambil.';
                            },
                            function () {
                                statusText.textContent = 'Tidak dapat mengambil lokasi. Pastikan izin lokasi diberikan.';
                            },
                            {
                                enableHighAccuracy: true,
                                timeout: 15000,
                            }
                        );
                    });
                }

                updateInputs(initialLat, initialLng);
                if (statusText) {
                    statusText.textContent = 'Peta berhasil dimuat. Klik peta atau gunakan lokasi perangkat.';
                }
            } catch (error) {
                if (statusText) {
                    statusText.textContent = 'Gagal memuat peta. Pastikan Leaflet berhasil dimuat.';
                }
                console.warn('Attendance location map init failed:', error);
            }
        };

        document.addEventListener('livewire:load', function () {
            window.attendanceLocationPickerInit();
        });

        document.addEventListener('livewire:update', function () {
            window.attendanceLocationPickerInit();
        });
    </script>
@endpush
