@extends('layouts.public')

@section('content')
    <section class="mx-auto max-w-xl px-4 py-20 sm:px-6 lg:px-8">
        <div class="mb-6 flex items-center justify-center flex-col gap-3">
            <img src="{{ asset('storage/logo/Logo Pramuka USU.png') }}" alt="Pramuka USU" width="96" height="96" style="width:96px;height:auto;max-width:100%;object-fit:contain;" class="object-contain" />
            <div class="text-center">
                <div class="text-xl font-bold text-[#5D4037]">SIPRAUSU</div>
                <div class="text-sm text-slate-500">Sistem Informasi Pramuka Universitas Sumatera Utara</div>
            </div>
        </div>
        <div class="surface-card rounded-3xl border border-slate-200 bg-white p-8 shadow-lg">
            <h1 class="text-2xl font-bold text-slate-900">Masuk</h1>
            <p class="mt-2 text-sm text-slate-600">Silakan masuk dengan email dan password akun Anda.</p>

            <form action="{{ route('login.store') }}" method="POST" class="mt-8 space-y-6">
                @csrf

                @if ($errors->any())
                    <div class="rounded-3xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 shadow-sm">
                        <div class="font-semibold">Login gagal</div>
                        <ul class="mt-2 list-disc space-y-1 pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div>
                    <label for="email" class="text-sm font-semibold text-slate-700">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus placeholder="Email Anda" class="mt-2 w-full rounded-3xl border border-slate-200 bg-slate-50 px-5 py-3 text-sm text-slate-900 shadow-sm outline-none transition duration-200 focus:border-[#5D4037] focus:bg-white focus:ring-2 focus:ring-[#5D4037]/20" />
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="text-sm font-semibold text-slate-700">Password</label>
                    <input id="password" name="password" type="password" required placeholder="Masukkan password" class="mt-2 w-full rounded-3xl border border-slate-200 bg-slate-50 px-5 py-3 text-sm text-slate-900 shadow-sm outline-none transition duration-200 focus:border-[#5D4037] focus:bg-white focus:ring-2 focus:ring-[#5D4037]/20" />
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between text-sm text-slate-600">
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300 text-[#5D4037] focus:ring-[#5D4037]" />
                        Remember me
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="font-semibold text-[#5D4037] hover:text-[#452a13]">Lupa password?</a>
                    @endif
                </div>

                <button type="submit" class="w-full rounded-2xl bg-[#5D4037] px-4 py-3 text-sm font-semibold text-white transition hover:bg-[#452a13]">Masuk</button>
            </form>
        </div>
    </section>
@endsection
