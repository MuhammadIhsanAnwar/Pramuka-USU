<?php

namespace App\Providers\Filament;

use App\Filament\User\Pages\EditProfile;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Foundation\Http\Middleware\ValidatePostSize;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\HtmlString;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class UserPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('user')
            ->path('dashboard')
            ->profile()
            ->brandName('Pramuka USU')
            ->brandLogoHeight('auto')
            ->brandLogo(fn (): HtmlString => new HtmlString(
                '<div class="fi-brand-logo">'
                . '<img src="' . asset('storage/logo/Logo Pramuka USU.png') . '" alt="SIPRAUSU" class="fi-brand-logo-image" />'
                . '<div class="fi-brand-logo-text">'
                . '<strong class="fi-brand-logo-title">SIPRAUSU</strong>'
                . '<span class="fi-brand-logo-subtitle">Sistem Informasi Pramuka Universitas Sumatera Utara</span>'
                . '</div>'
                . '</div>'
            ))
            ->darkMode(false)
            ->viteTheme('resources/css/app.css')
            ->colors([
                'primary' => Color::hex('#5D4037'),
                'warning' => Color::hex('#C9A227'),
                'gray' => Color::Neutral,
            ])
            ->font('Inter')
            ->profile(EditProfile::class, false)
            ->discoverPages(in: app_path('Filament/User/Pages'), for: 'App\\Filament\\User\\Pages')
            ->discoverWidgets(in: app_path('Filament/User/Widgets'), for: 'App\\Filament\\User\\Widgets')
            ->authMiddleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                ShareErrorsFromSession::class,
                ValidatePostSize::class,
                ConvertEmptyStringsToNull::class,
                SubstituteBindings::class,
            ], isPersistent: true);
    }
}