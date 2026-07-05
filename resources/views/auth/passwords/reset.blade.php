@extends('layouts.public')

@section('content')
    <section class="mx-auto max-w-xl px-4 py-20 sm:px-6 lg:px-8">
        <div class="surface-card rounded-3xl border border-slate-200 bg-white p-8 shadow-lg">
            <h1 class="text-2xl font-bold text-slate-900">Reset Password</h1>
            <p class="mt-2 text-sm text-slate-600">Masukkan password baru Anda untuk mengaktifkan kembali akun.</p>

            <form method="POST" action="{{ route('password.update') }}" class="mt-8 space-y-6">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div>
                    <label for="email" class="text-sm font-semibold text-slate-700">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $request->email) }}" required autofocus class="mt-2 w-full rounded-3xl border border-slate-200 bg-slate-50 px-5 py-3 text-sm text-slate-900 shadow-sm outline-none transition duration-200 focus:border-[#5D4037] focus:bg-white focus:ring-2 focus:ring-[#5D4037]/20" />
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="text-sm font-semibold text-slate-700">Password Baru</label>
                    <input id="password" name="password" type="password" required class="mt-2 w-full rounded-3xl border border-slate-200 bg-slate-50 px-5 py-3 text-sm text-slate-900 shadow-sm outline-none transition duration-200 focus:border-[#5D4037] focus:bg-white focus:ring-2 focus:ring-[#5D4037]/20" />
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="text-sm font-semibold text-slate-700">Konfirmasi Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required class="mt-2 w-full rounded-3xl border border-slate-200 bg-slate-50 px-5 py-3 text-sm text-slate-900 shadow-sm outline-none transition duration-200 focus:border-[#5D4037] focus:bg-white focus:ring-2 focus:ring-[#5D4037]/20" />
                </div>

                <button type="submit" class="w-full rounded-2xl bg-[#5D4037] px-4 py-3 text-sm font-semibold text-white transition hover:bg-[#452a13]">Reset Password</button>
            </form>
        </div>
    </section>
@endsection
