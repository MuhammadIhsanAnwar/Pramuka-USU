<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'Pramuka USU') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans">
    <div class="relative min-h-screen overflow-hidden">
        <div class="pointer-events-none absolute inset-x-0 top-0 -z-10 h-[32rem] bg-[radial-gradient(circle_at_top,_rgba(201,162,39,0.18),_transparent_55%),radial-gradient(circle_at_20%_20%,_rgba(93,64,55,0.12),_transparent_30%)]"></div>

        <header class="sticky top-0 z-50 border-b border-[#5D4037]/10 bg-white/90 backdrop-blur">
            <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#5D4037] text-sm font-bold text-white">USU</div>
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
                    <a href="{{ route('contact') }}" class="transition hover:text-[#5D4037]">Kontak</a>
                    <a href="{{ url('/admin') }}" class="btn-primary">Dashboard</a>
                </nav>

                <details class="relative md:hidden">
                    <summary class="cursor-pointer list-none rounded-full border border-[#5D4037]/10 bg-white px-4 py-2 text-sm font-semibold text-[#5D4037] shadow-sm">Menu</summary>
                    <div class="absolute right-0 mt-3 w-56 rounded-xl border border-[#5D4037]/10 bg-white p-3 shadow-xl">
                        <a href="{{ route('about') }}" class="block rounded-lg px-3 py-2 text-sm hover:bg-[#F5F5DC]">Tentang</a>
                        <a href="{{ route('news.index') }}" class="block rounded-lg px-3 py-2 text-sm hover:bg-[#F5F5DC]">Berita</a>
                        <a href="{{ route('agenda.index') }}" class="block rounded-lg px-3 py-2 text-sm hover:bg-[#F5F5DC]">Agenda</a>
                        <a href="{{ route('gallery.index') }}" class="block rounded-lg px-3 py-2 text-sm hover:bg-[#F5F5DC]">Galeri</a>
                        <a href="{{ route('contact') }}" class="block rounded-lg px-3 py-2 text-sm hover:bg-[#F5F5DC]">Kontak</a>
                        <a href="{{ url('/admin') }}" class="mt-2 block rounded-lg bg-[#5D4037] px-3 py-2 text-center text-sm font-semibold text-white">Dashboard</a>
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
                    <p class="mt-2 text-sm text-slate-600">Telepon: +62 61 12345678</p>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>