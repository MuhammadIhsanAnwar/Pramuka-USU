<x-filament-panels::page>
    <div class="flex items-center justify-center py-8">
        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm text-center">
            <h3 class="font-semibold mb-4">QR Kode Anda</h3>
            @if(auth()->user()->qr_code_url)
                <img src="{{ auth()->user()->qr_code_url }}" alt="QR User" class="mx-auto" />
            @else
                {!! \QrCode::size(200)->generate(auth()->user()->uuid) !!}
            @endif
            <p class="text-sm text-slate-600 mt-4">UUID: {{ auth()->user()->uuid }}</p>
        </div>
    </div>
</x-filament-panels::page>