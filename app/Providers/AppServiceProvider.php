<?php

namespace App\Providers;

use App\Models\EventAgenda;
use App\Models\Gallery;
use App\Models\NewsPost;
use App\Observers\EventAgendaObserver;
use App\Observers\GalleryObserver;
use App\Observers\NewsPostObserver;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::loginView('auth.login');
        Fortify::requestPasswordResetLinkView('auth.passwords.email');
        Fortify::resetPasswordView('auth.passwords.reset');

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::lower($request->input(Fortify::username()).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        NewsPost::observe(NewsPostObserver::class);
        EventAgenda::observe(EventAgendaObserver::class);
        Gallery::observe(GalleryObserver::class);
    }
}
