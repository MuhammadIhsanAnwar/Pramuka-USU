@php
    $brandName = filament()->getBrandName();
    $brandLogo = filament()->getBrandLogo();
    $brandLogoHeight = filament()->getBrandLogoHeight() ?? '1.5rem';
    $brandText = $brandName instanceof \Illuminate\Contracts\Support\Htmlable ? $brandName->toHtml() : e($brandName);
@endphp

<div {{ $attributes->class(['fi-logo', 'flex', 'items-center', 'gap-4']) }} style="height: {{ e($brandLogoHeight) }};">
    @if (filled($brandLogo))
        <img
            alt="{{ __('filament-panels::layout.logo.alt', ['name' => $brandName]) }}"
            src="{{ $brandLogo }}"
            class="shrink-0 object-contain"
            style="height: 2.4rem !important; width: auto !important; max-height: 2.4rem !important; margin-right: 0.35rem; display: block;"
        />
    @endif

    <div class="flex min-w-0 flex-col leading-none" style="font-size: inherit; line-height: 1.1; margin-left: 0.15rem;">
        <div style="font-size: 0.86rem; font-weight: 700; letter-spacing: 0.04em; color: #3E271A; text-transform: uppercase;">SIPRAUSU</div>
        <div style="margin-top: 0.14rem; font-size: 0.65rem; font-weight: 700; letter-spacing: 0.01em; color: #64748b;">Sistem Informasi Pramuka Universitas Sumatera Utara</div>
    </div>
</div>
