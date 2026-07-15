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
use Illuminate\View\Middleware\ShareErrorsFromSession;

class UserPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('user')
            ->path('user')
            ->profile()
            ->brandName('SIPRAUSU')
            ->brandLogo(asset('storage/logo/Logo Pramuka USU.png'))
            ->brandLogoHeight('1.75rem')
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