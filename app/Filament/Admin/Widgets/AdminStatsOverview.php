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
        $counts = Cache::remember('dashboard.admin.stats', now()->addMinutes(5), function (): array {
            return [
                'users' => User::query()->count(),
                'pembina' => User::query()->pembina()->count(),
                'peserta_didik' => User::query()->pesertaDidik()->count(),
                'news' => NewsPost::query()->count(),
                'attendance' => Attendance::query()->count(),
                'agendas' => EventAgenda::query()->count(),
            ];
        });

        return [
            Stat::make('Jumlah User', $counts['users']),
            Stat::make('Jumlah Pembina', $counts['pembina']),
            Stat::make('Jumlah Peserta Didik', $counts['peserta_didik']),
            Stat::make('Jumlah Berita', $counts['news']),
            Stat::make('Jumlah Presensi', $counts['attendance']),
            Stat::make('Jumlah Agenda', $counts['agendas']),
        ];
    }
}