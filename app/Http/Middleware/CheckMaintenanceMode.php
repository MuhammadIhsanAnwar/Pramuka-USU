<?php

namespace App\Http\Middleware;

use App\Models\SiteSetting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CheckMaintenanceMode
{
    public function handle(Request $request, Closure $next): Response
    {
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

        if (! $maintenanceMode) {
            return $next($request);
        }

        if ($request->is('admin*') || $request->routeIs(['login', 'login.store', 'password.request', 'password.email', 'password.reset'])) {
            return $next($request);
        }

        return response()->view('public.maintenance', [
            'siteName' => SiteSetting::query()->where('setting_key', 'site_name')->first()?->value[0] ?? 'Pramuka USU',
        ], 503);
    }
}
