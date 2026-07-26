@extends('layouts.public')

@section('content')
    <section class="mx-auto max-w-xl px-4 py-20 sm:px-6 lg:px-8">
        <div class="surface-card rounded-3xl border border-slate-200 bg-white p-8 text-center shadow-lg">
            <h1 class="text-2xl font-bold text-slate-900">Password berhasil diubah</h1>
            <p class="mt-2 text-sm text-slate-600">Silakan masuk kembali menggunakan password baru Anda.</p>
            <a href="{{ $loginUrl }}" class="mt-8 inline-flex rounded-2xl bg-[#5D4037] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#452a13]">Ke halaman login</a>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'success',
                title: 'Password berhasil diubah',
                text: 'Silakan masuk kembali menggunakan password baru Anda.',
                confirmButtonText: 'Ke halaman login',
                confirmButtonColor: '#5D4037',
                allowOutsideClick: false,
                allowEscapeKey: false,
                background: '#f8fafc',
                color: '#0f172a'
            }).then(function () {
                window.location.assign(@json($loginUrl));
            });
        });
    </script>
@endsection
