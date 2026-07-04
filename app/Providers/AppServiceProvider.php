<?php

namespace App\Providers;

use App\Models\EventAgenda;
use App\Models\Gallery;
use App\Models\NewsPost;
use App\Observers\EventAgendaObserver;
use App\Observers\GalleryObserver;
use App\Observers\NewsPostObserver;
use Illuminate\Support\ServiceProvider;

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
        NewsPost::observe(NewsPostObserver::class);
        EventAgenda::observe(EventAgendaObserver::class);
        Gallery::observe(GalleryObserver::class);
    }
}
