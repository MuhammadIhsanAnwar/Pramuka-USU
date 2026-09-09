@extends('layouts.public')

@section('content')
    <div class="mx-auto max-w-3xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="text-2xl font-semibold mb-4">QR Kode Anda</h3>

            @if(auth()->user()->qr_code_url)
                <div class="mx-auto w-fit rounded-2xl bg-slate-50 p-4">
                    <img src="{{ auth()->user()->qr_code_url }}" alt="QR User" class="mx-auto" />
                </div>
            @else
                <div class="mx-auto w-fit rounded-2xl bg-slate-50 p-4">
                    {!! \QrCode::size(200)->generate(auth()->user()->uuid) !!}
                </div>
            @endif

            <p class="text-sm text-slate-600 mt-6">UUID: {{ auth()->user()->uuid }}</p>
        </div>
    </div>
@endsection
