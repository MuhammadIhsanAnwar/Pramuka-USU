<?php

use App\Providers\AuthServiceProvider;
use App\Providers\AppServiceProvider;
use App\Providers\Filament\AdminPanelProvider;
use App\Providers\Filament\UserPanelProvider;

return [
    AuthServiceProvider::class,
    AppServiceProvider::class,
    AdminPanelProvider::class,
    UserPanelProvider::class,
];
