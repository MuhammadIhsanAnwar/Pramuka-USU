<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Attendance;
use App\Models\EventAgenda;
use App\Models\NewsPost;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return Cache::remember('dashboard.admin.stats', now()->addMinutes(5), function (): array {
            return [
                Stat::make('Jumlah User', User::query()->count()),
                Stat::make('Jumlah Pembina', User::query()->pembina()->count()),
                Stat::make('Jumlah Peserta Didik', User::query()->pesertaDidik()->count()),
                Stat::make('Jumlah Berita', NewsPost::query()->count()),
                Stat::make('Jumlah Presensi', Attendance::query()->count()),
                Stat::make('Jumlah Agenda', EventAgenda::query()->count()),
            ];
        });
    }
}