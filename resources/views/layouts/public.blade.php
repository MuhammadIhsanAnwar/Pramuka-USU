<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'Pramuka USU') }}</title>
    <link rel="shortcut icon" href="{{ asset('storage/logo/Logo Pramuka USU.png') }}" />
    <link rel="icon" type="image/png" href="{{ asset('storage/logo/Logo Pramuka USU.png') }}" />
    <link rel="apple-touch-icon" href="{{ asset('storage/logo/Logo Pramuka USU.png') }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans">
    @if (request()->routeIs('home'))
        <div id="homepage-loader" class="homepage-loader" aria-busy="true" aria-label="Memuat beranda Pramuka USU">
            <div class="homepage-loader__glow homepage-loader__glow--one" aria-hidden="true"></div>
            <div class="homepage-loader__glow homepage-loader__glow--two" aria-hidden="true"></div>

            <div class="homepage-loader__content">
                <div class="homepage-loader__emblem" aria-hidden="true">
                    <span class="homepage-loader__orbit homepage-loader__orbit--outer"></span>
                    <span class="homepage-loader__orbit homepage-loader__orbit--inner"></span>
                    <span class="homepage-loader__spark homepage-loader__spark--top">✦</span>
                    <span class="homepage-loader__spark homepage-loader__spark--bottom">✦</span>
                    <img src="{{ asset('storage/logo/Logo Pramuka USU.png') }}" alt="" class="homepage-loader__logo" />
                </div>

                <div class="homepage-loader__heading">
                    <p class="homepage-loader__eyebrow">Gugus Depan Gerakan Pramuka Kota Medan 08-137 dan 08-138</p>
                    <p class="homepage-loader__title">Pramuka USU</p>
                    <p class="homepage-loader__message" data-loader-message role="status">Menyiapkan pengalaman terbaik untuk Anda</p>
                </div>

                <div class="homepage-loader__progress-group">
                    <div class="homepage-loader__status-row">
                        <span class="homepage-loader__network">
                            <span class="homepage-loader__network-dot" aria-hidden="true"></span>
                            <span data-loader-network>Mengecek koneksi</span>
                        </span>
                        <span class="homepage-loader__percentage" data-loader-percentage>5%</span>
                    </div>

                    <div class="homepage-loader__track" role="progressbar" aria-label="Progres memuat beranda" aria-valuemin="0" aria-valuemax="100" aria-valuenow="5">
                        <div class="homepage-loader__bar" data-loader-bar></div>
                    </div>
                </div>

            <noscript><style>#homepage-loader { display: none !important; }</style></noscript>

            <script>
                (function () {
                    var loader = document.getElementById('homepage-loader');
                    if (!loader || loader.dataset.initialized === 'true') { return; }
                    loader.dataset.initialized = 'true';
                    var bar = loader.querySelector('[data-loader-bar]');
                    var progressElement = loader.querySelector('[role="progressbar"]');
                    var percentage = loader.querySelector('[data-loader-percentage]');
                    var message = loader.querySelector('[data-loader-message]');
                    var networkLabel = loader.querySelector('[data-loader-network]');
                    var connection = navigator.connection || navigator.mozConnection || navigator.webkitConnection;
                    var prefersReducedMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
                    var startedAt = Date.now();
                    var progress = 5;
                    var isComplete = false;
                    var animationFrame;

                    function getNetworkProfile() {
                        var type = connection && connection.effectiveType ? connection.effectiveType.toLowerCase() : '';
                        var downlink = connection && Number(connection.downlink) ? Number(connection.downlink) : null;
                        var rtt = connection && Number(connection.rtt) ? Number(connection.rtt) : null;
                        var saveData = Boolean(connection && connection.saveData);
                        if (!connection) { return { key: 'standard', label: 'Menyiapkan konten', message: 'Memuat aset penting untuk beranda', duration: 2600, cap: 88, timeout: 16000 }; }
                        if (saveData) { return { key: 'data-saver', label: 'Mode hemat data', message: 'Memuat aset penting terlebih dahulu', duration: 7000, cap: 78, timeout: 30000 }; }
                        if (type === 'slow-2g' || (downlink !== null && downlink < 0.5)) { return { key: 'slow', label: 'Koneksi sangat lambat', message: 'Koneksi lambat terdeteksi, mohon tunggu sebentar', duration: 10000, cap: 74, timeout: 30000 }; }
                        if (type === '2g' || (downlink !== null && downlink < 1.5)) { return { key: 'slow', label: 'Koneksi lambat', message: 'Memuat konten secara bertahap', duration: 7600, cap: 80, timeout: 26000 }; }
                        if (type === '3g' || (rtt !== null && rtt > 400) || (downlink !== null && downlink < 5)) { return { key: 'moderate', label: 'Koneksi sedang', message: 'Menyiapkan gambar dan konten beranda', duration: 4200, cap: 87, timeout: 20000 }; }
                        return { key: 'fast', label: 'Koneksi optimal', message: 'Menyiapkan pengalaman terbaik untuk Anda', duration: 1600, cap: 92, timeout: 12000 };
                    }

                    var profile = getNetworkProfile();
                    function updateProfile() {
                        profile = getNetworkProfile();
                        loader.dataset.network = profile.key;
                        if (networkLabel) { networkLabel.textContent = profile.label; }
                        if (message && progress < 55) { message.textContent = profile.message; }
                    }

                    function setProgress(value) {
                        progress = Math.min(100, Math.max(progress, Math.round(value)));
                        if (bar) { bar.style.setProperty('--loader-progress', progress + '%'); }
                        if (percentage) { percentage.textContent = progress + '%'; }
                        if (progressElement) { progressElement.setAttribute('aria-valuenow', String(progress)); }
                    }

                    function advanceProgress() {
                        if (isComplete) { return; }
                        var elapsed = Date.now() - startedAt;
                        var predicted = profile.cap * (1 - Math.exp(-elapsed / profile.duration));
                        setProgress(predicted);
                        animationFrame = window.requestAnimationFrame(advanceProgress);
                    }

                    function observePageAssets() {
                        var assets = Array.prototype.slice.call(document.images).filter(function (image) { return !loader.contains(image); });
                        if (assets.length === 0) { setProgress(70); return; }
                        var loadedAssets = 0;
                        var updateAssetProgress = function () {
                            loadedAssets += 1;
                            setProgress(36 + ((loadedAssets / assets.length) * 54));
                            if (loadedAssets === assets.length && message) { message.textContent = 'Sentuhan akhir sedang disiapkan'; }
                        };
                        assets.forEach(function (image) {
                            if (image.complete) { updateAssetProgress(); return; }
                            image.addEventListener('load', updateAssetProgress, { once: true });
                            image.addEventListener('error', updateAssetProgress, { once: true });
                        });
                    }

                    function finishLoading(isSafetyTimeout) {
                        if (isComplete) { return; }
                        isComplete = true;
                        window.cancelAnimationFrame(animationFrame);
                        setProgress(100);
                        loader.setAttribute('aria-busy', 'false');
                        if (message) { message.textContent = isSafetyTimeout ? 'Halaman siap digunakan' : 'Beranda siap, selamat datang'; }
                        var elapsed = Date.now() - startedAt;
                        var minimumDisplay = prefersReducedMotion ? 0 : Math.min(650, Math.max(280, profile.duration / 3));
                        var leaveAfter = Math.max(0, minimumDisplay - elapsed) + (prefersReducedMotion ? 0 : 180);
                        window.setTimeout(function () {
                            loader.classList.add('is-leaving');
                            window.setTimeout(function () { loader.remove(); }, prefersReducedMotion ? 0 : 500);
                        }, leaveAfter);
                    }

                    updateProfile();
                    setProgress(progress);
                    advanceProgress();
                    document.addEventListener('DOMContentLoaded', function () {
                        setProgress(30);
                        observePageAssets();
                        if (document.fonts && document.fonts.ready) { document.fonts.ready.then(function () { setProgress(65); }); }
                    }, { once: true });
                    window.addEventListener('load', function () { finishLoading(false); }, { once: true });
                    if (connection && connection.addEventListener) { connection.addEventListener('change', updateProfile); }
                    window.setTimeout(function () { finishLoading(true); }, profile.timeout);
                    if (document.readyState === 'complete') { finishLoading(false); }
                })();
            </script>
        </div>
    @endif

    <div class="relative min-h-screen overflow-hidden">
        <div class="pointer-events-none absolute inset-x-0 top-0 -z-10" style="height:32rem; background-image: radial-gradient(circle at top, rgba(201,162,39,0.18), transparent 55%), radial-gradient(circle at 20% 20%, rgba(93,64,55,0.12), transparent 30%);"></div>

        {{-- HEADER: Fixed positioning so it's always visible on scroll --}}
        <header class="fixed top-0 left-0 right-0 z-50 border-b border-[#5D4037]/10 bg-white/95 backdrop-blur-md shadow-sm">
            <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <img src="{{ asset('storage/logo/Logo Pramuka USU.png') }}" alt="Logo Pramuka USU" class="h-11 w-11 object-contain" />
                    <div>
                        <div class="text-sm font-bold tracking-wide text-[#5D4037]">Pramuka USU</div>
                        <div class="text-xs text-slate-500">Gugus Depan Gerakan Pramuka Kota Medan 08-137 dan 08-138</div>
                    </div>
                </a>

                <nav class="hidden items-center gap-6 text-sm font-medium text-slate-700 md:flex">
                    <a href="{{ route('about') }}" class="transition hover:text-[#5D4037]">Tentang</a>
                    <a href="{{ route('history') }}" class="transition hover:text-[#5D4037]">Sejarah</a>
                    <a href="{{ route('news.index') }}" class="transition hover:text-[#5D4037]">Berita</a>
                    <a href="{{ route('agenda.index') }}" class="transition hover:text-[#5D4037]">Agenda</a>
                    <a href="{{ route('gallery.index') }}" class="transition hover:text-[#5D4037]">Galeri</a>
                    <a href="{{ route('surat-masuk') }}" class="transition hover:text-[#5D4037]">Surat Masuk</a>
                    <a href="{{ route('contact') }}" class="transition hover:text-[#5D4037]">Kontak</a>
                    @auth
                        @if(auth()->user()->hasRole('Admin'))
                            <a href="{{ url('/admin') }}" class="btn-primary">Dashboard Admin</a>
                        @elseif(auth()->user()->hasRole('User'))
                            <a href="{{ url('/dashboard') }}" class="btn-primary">Dashboard User</a>
                        @else
                            <a href="{{ route('login') }}" class="btn-primary">Masuk</a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn-primary">Masuk</a>
                    @endauth
                </nav>

                <details class="relative md:hidden">
                    <summary class="cursor-pointer list-none rounded-full border border-[#5D4037]/10 bg-white px-4 py-2 text-sm font-semibold text-[#5D4037] shadow-sm">Menu</summary>
                    <div class="absolute right-0 mt-3 w-56 rounded-xl border border-[#5D4037]/10 bg-white p-3 shadow-xl">
                        <a href="{{ route('about') }}" class="block rounded-lg px-3 py-2 text-sm hover:bg-[#F5F5DC]">Tentang</a>
                        <a href="{{ route('history') }}" class="block rounded-lg px-3 py-2 text-sm hover:bg-[#F5F5DC]">Sejarah</a>
                        <a href="{{ route('news.index') }}" class="block rounded-lg px-3 py-2 text-sm hover:bg-[#F5F5DC]">Berita</a>
                        <a href="{{ route('agenda.index') }}" class="block rounded-lg px-3 py-2 text-sm hover:bg-[#F5F5DC]">Agenda</a>
                        <a href="{{ route('gallery.index') }}" class="block rounded-lg px-3 py-2 text-sm hover:bg-[#F5F5DC]">Galeri</a>
                        <a href="{{ route('surat-masuk') }}" class="block rounded-lg px-3 py-2 text-sm hover:bg-[#F5F5DC]">Surat Masuk</a>
                        <a href="{{ route('contact') }}" class="block rounded-lg px-3 py-2 text-sm hover:bg-[#F5F5DC]">Kontak</a>
                        @auth
                            @if(auth()->user()->hasRole('Admin'))
                                <a href="{{ url('/admin') }}" class="mt-2 block rounded-lg bg-[#5D4037] px-3 py-2 text-center text-sm font-semibold text-white">Dashboard Admin</a>
                            @elseif(auth()->user()->hasRole('User'))
                                <a href="{{ url('/dashboard') }}" class="mt-2 block rounded-lg bg-[#5D4037] px-3 py-2 text-center text-sm font-semibold text-white">Dashboard User</a>
                            @else
                                <a href="{{ route('login') }}" class="mt-2 block rounded-lg bg-[#5D4037] px-3 py-2 text-center text-sm font-semibold text-white">Masuk</a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="mt-2 block rounded-lg bg-[#5D4037] px-3 py-2 text-center text-sm font-semibold text-white">Masuk</a>
                        @endauth
                    </div>
                </details>
            </div>
        </header>

        {{-- Add top padding to main content so fixed header doesn't overlap --}}
        <main class="pt-20">
            @yield('content')
        </main>

        <footer class="mt-20 border-t border-[#5D4037]/10 bg-white">
            <div class="mx-auto grid max-w-7xl gap-10 px-4 py-12 sm:px-6 lg:grid-cols-3 lg:px-8">
                <div>
                    <div class="text-lg font-bold text-[#5D4037]">Pramuka USU</div>
                    <p class="mt-3 max-w-md text-sm leading-6 text-slate-600">Website resmi Gugus Depan Gerakan Pramuka Kota Medan 08-137 dan 08-138 untuk publikasi berita, agenda, galeri, dan layanan anggota.</p>
                    <p class="mt-3 text-sm font-semibold text-[#5D4037]">#SabhaBersaudara</p>
                </div>
                <div>
                    <div class="text-sm font-semibold uppercase tracking-[0.2em] text-[#5D4037]">Navigasi</div>
                    <div class="mt-4 flex flex-col gap-3 text-sm text-slate-600">
                        <a href="{{ route('home') }}">Home</a>
                        <a href="{{ route('about') }}">Tentang Kami</a>
                        <a href="{{ route('history') }}">Sejarah</a>
                        <a href="{{ route('news.index') }}">Berita</a>
                        <a href="{{ route('agenda.index') }}">Agenda</a>
                        <a href="{{ route('surat-masuk') }}">Surat Masuk</a>
                    </div>
                </div>
                <div>
                    <div class="text-sm font-semibold uppercase tracking-[0.2em] text-[#5D4037]">Kontak</div>
                    <p class="mt-4 text-sm text-slate-600">Email: pramuka@usu.ac.id</p>
                    <div class="mt-4 flex flex-wrap items-center gap-3">
                        <a href="https://linktr.ee/PramukaUSU" target="_blank" rel="noopener" class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-[#F5F5DC]/40 transition hover:bg-[#5D4037]/10 hover:border-[#5D4037]">
                            <img src="{{ asset('storage/ikon/Linktree copy.png') }}" alt="Linktree" class="h-6 w-6 object-contain" />
                        </a>
                        <a href="https://instagram.com/pramuka_usu" target="_blank" rel="noopener" class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-[#F5F5DC]/40 transition hover:bg-[#5D4037]/10 hover:border-[#5D4037]">
                            <img src="{{ asset('storage/ikon/Instagram copy.png') }}" alt="Instagram" class="h-6 w-6 object-contain" />
                        </a>
                        <a href="https://facebook.com/pramukausu1974" target="_blank" rel="noopener" class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-[#F5F5DC]/40 transition hover:bg-[#5D4037]/10 hover:border-[#5D4037]">
                            <img src="{{ asset('storage/ikon/Facebook copy.png') }}" alt="Facebook" class="h-6 w-6 object-contain" />
                        </a>
                        <a href="https://threads.com/@pramuka_usu" target="_blank" rel="noopener" class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-[#F5F5DC]/40 transition hover:bg-[#5D4037]/10 hover:border-[#5D4037]">
                            <img src="{{ asset('storage/ikon/Threads copy.png') }}" alt="Threads" class="h-6 w-6 object-contain" />
                        </a>
                        <a href="https://tiktok.com/@pramuka_usu" target="_blank" rel="noopener" class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-[#F5F5DC]/40 transition hover:bg-[#5D4037]/10 hover:border-[#5D4037]">
                            <img src="{{ asset('storage/ikon/Tiktok copy.png') }}" alt="TikTok" class="h-6 w-6 object-contain" />
                        </a>
                        <a href="https://youtube.com/@pramuka_usu" target="_blank" rel="noopener" class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-[#F5F5DC]/40 transition hover:bg-[#5D4037]/10 hover:border-[#5D4037]">
                            <img src="{{ asset('storage/ikon/Youtube copy.png') }}" alt="YouTube" class="h-6 w-6 object-contain" />
                        </a>
                    </div>
                </div>
            </div>

            {{-- Copyright bar with dynamic year --}}
            <div class="border-t border-[#5D4037]/10 bg-[#F5F5DC]/30 px-4 py-5">
                <div class="mx-auto max-w-7xl text-center text-sm text-slate-600">
                    &copy; {{ date('Y') }} Pramuka USU. Created by Muhammad Ihsan Anwar - 251402044. All Right Reserved.
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
