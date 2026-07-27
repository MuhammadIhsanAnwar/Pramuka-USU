<?php

namespace App\Providers;

use App\Actions\Fortify\ResetUserPassword;
use App\Enums\RoleName;
use App\Models\EventAgenda;
use App\Models\Gallery;
use App\Models\NewsPost;
use App\Models\SiteSetting;
use App\Models\User;
use App\Observers\EventAgendaObserver;
use App\Observers\GalleryObserver;
use App\Observers\NewsPostObserver;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Laravel\Fortify\Contracts\LogoutResponse as LogoutResponseContract;
use Laravel\Fortify\Contracts\RedirectsIfTwoFactorAuthenticatable;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Contracts\PasswordResetResponse as PasswordResetResponseContract;
use App\Http\Responses\PasswordResetResponse as AppPasswordResetResponse;
use App\Http\Responses\LoginResponse as AppLoginResponse;
use App\Http\Responses\LogoutResponse as AppLogoutResponse;
use Filament\Auth\Http\Responses\Contracts\LogoutResponse as FilamentLogoutResponseContract;
use App\Http\Responses\FilamentLogoutResponse;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->scoped(StatefulGuard::class, function () {
            return Auth::guard(config('fortify.guard', 'web'));
        });

        $this->app->singleton(LoginResponseContract::class, AppLoginResponse::class);
        $this->app->singleton(LogoutResponseContract::class, AppLogoutResponse::class);
        // Override Filament logout response to redirect to public homepage
        $this->app->singleton(FilamentLogoutResponseContract::class, FilamentLogoutResponse::class);
        $this->app->singleton(RedirectsIfTwoFactorAuthenticatable::class, RedirectIfTwoFactorAuthenticatable::class);
        $this->app->singleton(PasswordResetResponseContract::class, AppPasswordResetResponse::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::loginView('auth.login');
        Fortify::authenticateUsing(function (Request $request) {
            $user = User::where('email', $request->email)->first();

            if (! $user || ! Hash::check($request->password, $user->password)) {
                return null;
            }

            $setting = SiteSetting::query()
                ->where('setting_key', 'maintenance_mode')
                ->first();

            $maintenanceMode = false;

            if ($setting !== null) {
                $value = $setting->value;

                if (is_array($value)) {
                    $maintenanceMode = filter_var($value[0] ?? false, FILTER_VALIDATE_BOOLEAN);
                } else {
                    $maintenanceMode = filter_var($value, FILTER_VALIDATE_BOOLEAN);
                }
            }

            if ($maintenanceMode && ! $user->hasRole(RoleName::Admin->value)) {
                throw ValidationException::withMessages([
                    Fortify::username() => ['Website sedang pemeliharaan. Hanya administrator yang dapat masuk saat ini.'],
                ]);
            }

            return $user;
        });
        Fortify::requestPasswordResetLinkView('auth.passwords.email');
        Fortify::resetPasswordView('auth.passwords.reset');

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::lower($request->input(Fortify::username()) . '|' . $request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        NewsPost::observe(NewsPostObserver::class);
        EventAgenda::observe(EventAgendaObserver::class);
        Gallery::observe(GalleryObserver::class);
    }
}
