@extends('layouts.public')

@section('content')
    <section class="mx-auto max-w-3xl px-4 py-16 sm:px-6 lg:px-8 lg:py-24">
        <article class="surface-card overflow-hidden rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            <div class="mb-6">
                <div class="text-xs font-semibold uppercase tracking-[0.2em] text-[#5D4037]">Presensi QR</div>
                <h1 class="mt-3 text-3xl font-extrabold text-slate-900">{{ $eventAgenda->name }}</h1>
                <p class="mt-2 text-sm text-slate-600">Pastikan foto wajah Anda jelas dan lokasi GPS menunjukkan bahwa Anda berada di dalam radius kegiatan.</p>
            </div>

            @if (session('status'))
                <div class="mb-6 rounded-3xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-3xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">
                    <div class="font-semibold">Perbaiki kesalahan berikut:</div>
                    <ul class="mt-2 list-disc space-y-1 pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ $signedSubmitUrl }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700" for="photo">Foto Bukti</label>
                    <input id="photo" name="photo" type="file" accept="image/*" required class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200" />
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700" for="latitude">Latitude</label>
                        <input id="latitude" name="latitude" type="text" value="{{ old('latitude') }}" readonly required class="block w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-700 shadow-sm" />
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700" for="longitude">Longitude</label>
                        <input id="longitude" name="longitude" type="text" value="{{ old('longitude') }}" readonly required class="block w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-700 shadow-sm" />
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700" for="notes">Catatan</label>
                    <textarea id="notes" name="notes" rows="4" class="block w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200">{{ old('notes') }}</textarea>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-700">
                    <div class="font-semibold text-slate-900">Lokasi Presensi</div>
                    <p class="mt-2">Radius presensi: {{ $eventAgenda->radius ?? 500 }} meter</p>
                    <p class="mt-1">Koordinat target: {{ $eventAgenda->latitude }}, {{ $eventAgenda->longitude }}</p>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <button type="button" id="getLocation" class="inline-flex items-center justify-center rounded-2xl bg-slate-800 px-5 py-3 text-sm font-semibold text-white shadow-sm hover:bg-slate-700">Ambil Lokasi Sekarang</button>
                    <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500">Kirim Presensi</button>
                </div>
            </form>
        </article>
    </section>

    <script>
        document.getElementById('getLocation').addEventListener('click', function () {
            if (! navigator.geolocation) {
                alert('Geolocation tidak didukung di browser Anda.');
                return;
            }

            navigator.geolocation.getCurrentPosition(function (position) {
                document.getElementById('latitude').value = position.coords.latitude.toFixed(7);
                document.getElementById('longitude').value = position.coords.longitude.toFixed(7);
            }, function () {
                alert('Tidak dapat mengambil lokasi. Pastikan Anda memperbolehkan akses lokasi.');
            }, {
                enableHighAccuracy: true,
                timeout: 15000,
            });
        });
    </script>
@endsection
