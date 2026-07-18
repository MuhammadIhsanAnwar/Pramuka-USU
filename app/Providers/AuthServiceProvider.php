<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Gate::before(static function (User $user, string $ability): ?bool {
            if (in_array($ability, ['access-admin-dashboard', 'access-user-dashboard'], true)) {
                return null;
            }

            return $user->hasRole('Admin') ? true : null;
        });

        Gate::define('access-admin-dashboard', static function (User $user): bool {
            return $user->hasRole('Admin');
        });

        Gate::define('access-user-dashboard', static function (User $user): bool {
            return $user->hasRole('User') && ! $user->hasRole('Admin');
        });
    }
}