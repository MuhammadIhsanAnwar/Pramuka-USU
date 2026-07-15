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
        <div id="homepage-loader" role="status" aria-live="polite" aria-label="Memuat halaman"
             style="position:fixed;inset:0;display:flex;align-items:center;justify-content:center;background:linear-gradient(180deg,rgba(255,255,255,0.98),rgba(248,244,234,0.95));z-index:99999;transition:opacity 420ms ease,visibility 420ms ease;backdrop-filter:blur(3px)">
            <div class="loader-inner" style="display:flex;flex-direction:column;align-items:center;gap:10px">
                <img src="{{ asset('storage/logo/Logo Pramuka USU.png') }}" alt="Pramuka USU" style="width:96px;height:96px;object-fit:contain" />
                <div class="brand-text" style="font-weight:700;color:#5D4037;font-size:16px">Pramuka USU</div>
                <div class="loader-progress" aria-hidden="true" style="width:220px;height:8px;border-radius:999px;background:rgba(0,0,0,0.06);overflow:hidden;margin-top:12px">
                    <div class="loader-progress-bar" style="width:0%;height:100%;background:linear-gradient(90deg,#5D4037 0%,#C9A227 60%);transition:width 180ms linear"></div>
                </div>
            </div>
        </div>

        {{-- Inline fallback script: network-aware progress and auto-hide when compiled assets are missing or slow --}}
        <script>
            if (!window.__homepage_loader_inline) {
                window.__homepage_loader_inline = true;
                (function(){
                    var loader = document.getElementById('homepage-loader');
                    if (!loader) return;
                    var progressBar = loader.querySelector('.loader-progress-bar');
                    var conn = navigator.connection || navigator.mozConnection || navigator.webkitConnection || null;
                    var estimateMs = 2000;
                    if (conn) {
                        var t = (conn.effectiveType || '').toLowerCase();
                        var downlink = conn.downlink || 10;
                        if (t.indexOf('slow-2g') !== -1) estimateMs = 12000;
                        else if (t.indexOf('2g') !== -1) estimateMs = 9000;
                        else if (t.indexOf('3g') !== -1) estimateMs = 5000;
                        else if (t.indexOf('4g') !== -1) estimateMs = 1500;
                        estimateMs = Math.max(1000, Math.round(estimateMs * (1 / Math.max(0.3, Math.min(downlink / 10, 2)))));
                    }
                    var percent = 4;
                    if (progressBar) progressBar.style.width = percent + '%';
                    var start = Date.now();
                    var intervalId = setInterval(function(){
                        var elapsed = Date.now() - start;
                        var target = Math.min(99, Math.round((elapsed / estimateMs) * 100));
                        if (target > percent) percent = target; else percent = Math.min(99, percent + Math.random() * 3);
                        if (progressBar) progressBar.style.width = percent + '%';
                    }, 200);
                    var finished = false;
                    var finish = function(){
                        if (finished) return; finished = true;
                        clearInterval(intervalId);
                        if (progressBar) progressBar.style.width = '100%';
                        setTimeout(function(){ loader.style.opacity = 0; loader.style.visibility = 'hidden'; setTimeout(function(){ if (loader.parentNode) loader.remove(); }, 480); }, 160);
                    };
                    window.addEventListener('load', finish);
                    setTimeout(function(){ if (document.body.contains(loader)) finish(); }, Math.min(20000, Math.max(8000, estimateMs * 3)));
                })();
            }
        </script>
    @endif
    <div class="relative min-h-screen overflow-hidden">
        <div class="pointer-events-none absolute inset-x-0 top-0 -z-10" style="height:32rem; background-image: radial-gradient(circle at top, rgba(201,162,39,0.18), transparent 55%), radial-gradient(circle at 20% 20%, rgba(93,64,55,0.12), transparent 30%);"></div>

        <header class="sticky top-0 z-50 border-b border-[#5D4037]/10 bg-white/90 backdrop-blur">
            <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <img src="{{ asset('storage/logo/Logo Pramuka USU.png') }}" alt="Logo Pramuka USU" class="h-11 w-11 object-contain" />
                    <div>
                        <div class="text-sm font-bold tracking-wide text-[#5D4037]">Pramuka USU</div>
                        <div class="text-xs text-slate-500">Gerakan Pramuka Universitas Sumatera Utara</div>
                    </div>
                </a>

                <nav class="hidden items-center gap-6 text-sm font-medium text-slate-700 md:flex">
                    <a href="{{ route('about') }}" class="transition hover:text-[#5D4037]">Tentang</a>
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

        <main>
            @yield('content')
        </main>

        <footer class="mt-20 border-t border-[#5D4037]/10 bg-white">
            <div class="mx-auto grid max-w-7xl gap-10 px-4 py-12 sm:px-6 lg:grid-cols-3 lg:px-8">
                <div>
                    <div class="text-lg font-bold text-[#5D4037]">Pramuka USU</div>
                    <p class="mt-3 max-w-md text-sm leading-6 text-slate-600">Website resmi Gerakan Pramuka Universitas Sumatera Utara untuk publikasi berita, agenda, galeri, dan layanan anggota.</p>
                    <p class="mt-3 text-sm font-semibold text-[#5D4037]">#SabhaBersaudara</p>
                </div>
                <div>
                    <div class="text-sm font-semibold uppercase tracking-[0.2em] text-[#5D4037]">Navigasi</div>
                    <div class="mt-4 flex flex-col gap-3 text-sm text-slate-600">
                        <a href="{{ route('home') }}">Home</a>
                        <a href="{{ route('about') }}">Tentang Kami</a>
                        <a href="{{ route('news.index') }}">Berita</a>
                        <a href="{{ route('agenda.index') }}">Agenda</a>
                    </div>
                </div>
                <div>
                    <div class="text-sm font-semibold uppercase tracking-[0.2em] text-[#5D4037]">Kontak</div>
                    <p class="mt-4 text-sm text-slate-600">Email: pramuka@usu.ac.id</p>
                    <div class="mt-4 space-y-3 text-sm text-slate-600">
                                <div class="flex items-center gap-3">
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
            </div>
        </footer>
    </div>
</body>
</html>