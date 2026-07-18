<?php

namespace App\Providers\Filament;

use App\Filament\Admin\Pages\EditProfile;
use Filament\Enums\UserMenuPosition;
use Filament\Http\Middleware\Authenticate;
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

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->profile(EditProfile::class)
            ->topbar(true)
            ->userMenu(true, UserMenuPosition::Topbar)
            ->darkMode(false)
            ->renderHook('panels::topbar.start', fn() => view('filament.admin.topbar-brand'))
            ->renderHook('panels::styles.after', fn() => view('filament.overrides'))
            ->renderHook('panels::scripts.after', fn() => view('filament.overrides-js'))
            ->colors([
                'primary' => Color::hex('#3E271A'),
                'warning' => Color::hex('#C9A227'),
                'gray' => Color::Neutral,
            ])
            ->font('Inter')
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\\Filament\\Admin\\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admin\\Pages')
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\\Filament\\Admin\\Widgets')
            ->authMiddleware([
                Authenticate::class,
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