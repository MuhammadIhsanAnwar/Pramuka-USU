<div class="rounded-xl border border-slate-200 bg-slate-50 p-4 shadow-sm">
    <div class="mb-3 text-sm font-semibold text-slate-900">Pilih titik lokasi</div>
    <div id="event-agenda-location-map" class="h-72 rounded-xl border border-slate-300"></div>
    <div class="mt-3 text-xs text-slate-500">Seret penanda atau klik pada peta untuk menetapkan latitude dan longitude.</div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-sA+e2THo0XJTTQaUakHj2yP5Q0X0w/2RwV+6E1v7kDU=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-o9N1j2n1+cp231vPJv9CEwywzjpn3pWBXLE9m+dwYyM=" crossorigin=""></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (window.eventAgendaLocationPickerInitialized) {
            return;
        }

        window.eventAgendaLocationPickerInitialized = true;

        const latInput = document.getElementById('event-agenda-latitude');
        const lngInput = document.getElementById('event-agenda-longitude');
        const mapContainer = document.getElementById('event-agenda-location-map');

        if (! latInput || ! lngInput || ! mapContainer) {
            return;
        }

        const initialLat = parseFloat(latInput.value) || -0.5100;
        const initialLng = parseFloat(lngInput.value) || 98.6626;
        const initialZoom = 13;

        const map = L.map(mapContainer).setView([initialLat, initialLng], initialZoom);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors',
        }).addTo(map);

        const marker = L.marker([initialLat, initialLng], {
            draggable: true,
        }).addTo(map);

        const updateInputs = (lat, lng) => {
            latInput.value = lat.toFixed(7);
            lngInput.value = lng.toFixed(7);
            latInput.dispatchEvent(new Event('change'));
            lngInput.dispatchEvent(new Event('change'));
        };

        marker.on('dragend', function (event) {
            const position = event.target.getLatLng();
            updateInputs(position.lat, position.lng);
        });

        map.on('click', function (event) {
            const position = event.latlng;
            marker.setLatLng(position);
            updateInputs(position.lat, position.lng);
        });
    });
</script>
