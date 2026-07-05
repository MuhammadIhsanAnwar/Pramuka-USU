@extends('layouts.public')

@section('content')
    <section class="mx-auto max-w-xl px-4 py-20 sm:px-6 lg:px-8">
        <div class="surface-card rounded-3xl border border-slate-200 bg-white p-8 shadow-lg">
            <h1 class="text-2xl font-bold text-slate-900">Lupa Password</h1>
            <p class="mt-2 text-sm text-slate-600">Masukkan email Anda untuk menerima tautan reset password.</p>

            <form action="{{ route('password.email') }}" method="POST" class="mt-8 space-y-6">
                @csrf

                <div>
                    <label for="email" class="text-sm font-semibold text-slate-700">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus placeholder="Email Anda" class="mt-2 w-full rounded-3xl border border-slate-200 bg-slate-50 px-5 py-3 text-sm text-slate-900 shadow-sm outline-none transition duration-200 focus:border-[#5D4037] focus:bg-white focus:ring-2 focus:ring-[#5D4037]/20" />
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between text-sm text-slate-600">
                    <a href="{{ route('login') }}" class="font-semibold text-[#5D4037] hover:text-[#452a13]">Kembali ke login</a>
                </div>

                <button type="submit" class="w-full rounded-2xl bg-[#5D4037] px-4 py-3 text-sm font-semibold text-white transition hover:bg-[#452a13]">Kirim tautan reset password</button>
            </form>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if (session('status'))
                Swal.fire({
                    icon: 'success',
                    title: 'Permintaan reset password berhasil',
                    text: {!! json_encode(__((string) session('status'))) !!},
                    confirmButtonColor: '#5D4037',
                    background: '#f8fafc',
                    color: '#0f172a'
                });
            @elseif ($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi kesalahan',
                    text: {!! json_encode($errors->first()) !!},
                    confirmButtonColor: '#5D4037',
                    background: '#f8fafc',
                    color: '#0f172a'
                });
            @endif
        });
    </script>
@endsection
