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

    <style>
        .public-navbar-logout-action:hover {
            background-color: #fee2e2 !important;
            color: #b91c1c !important;
        }
        .public-navbar-logout-action:hover .inline-flex {
            color: #b91c1c !important;
        }
    </style>

    @if (request()->routeIs('home'))
    <style>
        /* Critical inline styles for homepage loader — ensures full-screen coverage even if external CSS fails to load */
        #homepage-loader {
            position: fixed !important;
            inset: 0 !important;
            z-index: 99999 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            isolation: isolate !important;
            overflow: hidden !important;
            padding: 1.5rem !important;
            background: radial-gradient(circle at 50% 24%, rgba(201,162,39,0.2), transparent 26rem), radial-gradient(circle at 12% 88%, rgba(93,64,55,0.12), transparent 24rem), linear-gradient(145deg, #fffdf8 0%, #f8f1e2 52%, #eee1c7 100%) !important;
            transition: opacity 0.42s ease, visibility 0.42s ease, transform 0.42s ease !important;
        }
        #homepage-loader.is-leaving {
            opacity: 0 !important;
            visibility: hidden !important;
            transform: scale(1.015) !important;
            pointer-events: none !important;
        }
    </style>
    @endif
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
        <header id="site-header" class="fixed top-0 left-0 right-0 z-50 border-b border-[#5D4037]/10 bg-white/95 backdrop-blur-md shadow-sm">
            <div class="mx-auto flex max-w-8xl items-center justify-between gap-4 px-4 py-2 sm:px-6 lg:px-8">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <img src="{{ asset('storage/logo/Logo Pramuka USU.png') }}" alt="Logo Pramuka USU" class="h-10 w-10 object-contain" />
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
                        @php
                            $dashboardUrl = auth()->user()->hasRole('Admin') ? url('/admin') : url('/dashboard');
                            $profileUrl = auth()->user()->hasRole('Admin') ? url('/admin/profile') : url('/user/profile');
                        @endphp

                        <details class="relative">
                            <summary class="inline-flex items-center gap-2 rounded-full border border-[#5D4037]/10 bg-white px-3 py-2 text-sm font-semibold text-[#5D4037] shadow-sm transition hover:border-[#5D4037]/20">
                                <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="h-10 w-10 rounded-2xl object-cover" />
                                <span class="hidden truncate md:inline-block max-w-[10rem]">{{ auth()->user()->name }}</span>
                                <span class="text-xs">▾</span>
                            </summary>

                            <div class="absolute right-0 z-50 mt-2 w-56 overflow-hidden rounded-2xl border border-[#5D4037]/10 bg-white text-sm shadow-xl">
                                <div class="border-b border-[#5D4037]/10 px-4 py-3 text-left whitespace-normal break-words">
                                    <p class="text-sm font-semibold text-slate-900 leading-5 break-words">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-slate-500 leading-4 break-words">{{ auth()->user()->email }}</p>
                                </div>
                                <a href="{{ $dashboardUrl }}" class="flex items-center gap-3 px-4 py-3 text-slate-700 transition hover:bg-[#F5F5DC]">
                                    <span class="inline-flex h-5 w-5 items-center justify-center text-[#5D4037]">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="M3 13h2v7h14v-7h2"/><path d="M7 13V6h10v7"/><path d="M8 10h8"/></svg>
                                    </span>
                                    Dashboard
                                </a>
                                <a href="{{ $profileUrl }}" class="flex items-center gap-3 px-4 py-3 text-slate-700 transition hover:bg-[#F5F5DC]">
                                    <span class="inline-flex h-5 w-5 items-center justify-center text-[#5D4037]">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                    </span>
                                    Profil
                                </a>
                                <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-3 text-slate-700 transition hover:bg-[#F5F5DC]">
                                    <span class="inline-flex h-5 w-5 items-center justify-center text-[#5D4037]">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="M3 9.5L12 3l9 6.5V20a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1V9.5z"/></svg>
                                    </span>
                                    Beranda Utama
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="public-navbar-logout-action flex w-full items-center gap-3 text-left px-4 py-3 text-slate-700 transition hover:bg-[#fee2e2] hover:text-[#b91c1c]">
                                        <span class="inline-flex h-5 w-5 items-center justify-center text-[#5D4037]">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                                        </span>
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </details>
                    @else
                        <a href="{{ route('login') }}" class="btn-primary">Masuk</a>
                    @endauth
                </nav>

                <details class="relative md:hidden">
                    <summary class="cursor-pointer list-none rounded-full border border-[#5D4037]/10 bg-white px-4 py-2 text-sm font-semibold text-[#5D4037] shadow-sm">Menu</summary>
                    <div class="absolute right-0 z-50 mt-3 w-56 rounded-xl border border-[#5D4037]/10 bg-white p-3 shadow-xl"
                         style="max-height: calc(100vh - 6rem); overflow-y: auto; overflow-x: hidden; touch-action: pan-y; -webkit-overflow-scrolling: touch; overscroll-behavior: contain;">
                        @auth
                            <div class="mb-3 rounded-2xl border border-[#E5E7EB] bg-slate-50 px-3 py-3 text-center">
                                <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="mx-auto mb-3 h-10 w-10 rounded-2xl object-cover" />
                                <p class="text-sm font-semibold text-slate-900 leading-5 break-words">{{ auth()->user()->name }}</p>
                                <p class="mt-1 text-xs text-slate-500 leading-4 break-words">{{ auth()->user()->email }}</p>
                            </div>
                        @endauth
                        <a href="{{ route('about') }}" class="mt-2 block rounded-xl px-3 py-3 text-left text-sm text-slate-700 transition hover:bg-[#F5F5DC] hover:rounded-2xl">Tentang</a>
                        <a href="{{ route('history') }}" class="block rounded-xl px-3 py-3 text-left text-sm text-slate-700 transition hover:bg-[#F5F5DC] hover:rounded-2xl">Sejarah</a>
                        <a href="{{ route('news.index') }}" class="block rounded-xl px-3 py-3 text-left text-sm text-slate-700 transition hover:bg-[#F5F5DC] hover:rounded-2xl">Berita</a>
                        <a href="{{ route('agenda.index') }}" class="block rounded-xl px-3 py-3 text-left text-sm text-slate-700 transition hover:bg-[#F5F5DC] hover:rounded-2xl">Agenda</a>
                        <a href="{{ route('gallery.index') }}" class="block rounded-xl px-3 py-3 text-left text-sm text-slate-700 transition hover:bg-[#F5F5DC] hover:rounded-2xl">Galeri</a>
                        <a href="{{ route('surat-masuk') }}" class="block rounded-xl px-3 py-3 text-left text-sm text-slate-700 transition hover:bg-[#F5F5DC] hover:rounded-2xl">Surat Masuk</a>
                        <a href="{{ route('contact') }}" class="block rounded-xl px-3 py-3 text-left text-sm text-slate-700 transition hover:bg-[#F5F5DC] hover:rounded-2xl">Kontak</a>
                        @auth
                            <a href="{{ auth()->user()->hasRole('Admin') ? url('/admin') : url('/dashboard') }}" class="mt-2 flex items-center gap-3 rounded-xl px-3 py-3 text-left text-sm text-slate-700 transition hover:bg-[#F5F5DC] hover:rounded-2xl">
                                <span class="inline-flex h-5 w-5 items-center justify-center text-[#5D4037]">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="M3 13h2v7h14v-7h2"/><path d="M7 13V6h10v7"/><path d="M8 10h8"/></svg>
                                </span>
                                Dashboard
                            </a>
                            <a href="{{ auth()->user()->hasRole('Admin') ? url('/admin/profile') : url('/user/profile') }}" class="mt-2 flex items-center gap-3 rounded-xl px-3 py-3 text-left text-sm text-slate-700 transition hover:bg-[#F5F5DC] hover:rounded-2xl">
                                <span class="inline-flex h-5 w-5 items-center justify-center text-[#5D4037]">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                </span>
                                Profil
                            </a>
                            <a href="{{ route('home') }}" class="mt-2 flex items-center gap-3 rounded-xl px-3 py-3 text-left text-sm text-slate-700 transition hover:bg-[#F5F5DC] hover:rounded-2xl">
                                <span class="inline-flex h-5 w-5 items-center justify-center text-[#5D4037]">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="M3 9.5L12 3l9 6.5V20a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1V9.5z"/></svg>
                                </span>
                                Beranda Utama
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                                @csrf
                                <button type="submit" class="public-navbar-logout-action w-full flex items-center gap-3 rounded-xl px-3 py-3 text-left text-sm font-semibold text-slate-700 transition hover:bg-[#fee2e2] hover:text-[#b91c1c] hover:rounded-2xl">
                                    <span class="inline-flex h-5 w-5 items-center justify-center text-[#5D4037]">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                                    </span>
                                    Keluar
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="mt-2 block rounded-lg bg-[#5D4037] px-3 py-2 text-center text-sm font-semibold text-white">Masuk</a>
                        @endauth
                    </div>
                </details>
            </div>
        </header>

        <script>
            function updateHeaderPadding() {
                var header = document.getElementById('site-header');
                if (!header) { return; }
                document.documentElement.style.setProperty('--header-height', header.offsetHeight + 'px');
            }
            window.addEventListener('load', updateHeaderPadding);
            window.addEventListener('resize', updateHeaderPadding);
            if (document.readyState === 'complete') {
                updateHeaderPadding();
            } else {
                document.addEventListener('DOMContentLoaded', updateHeaderPadding);
            }
        </script>

        {{-- Add top padding to main content so fixed header doesn't overlap --}}
        <main class="pt-20" style="padding-top: var(--header-height, 5rem);">
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
