<?php

namespace App\Filament\User\Widgets;

use App\Models\Attendance;
use App\Models\EventAgenda;
use App\Models\NewsPost;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class UserStatsOverviewWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $user = Auth::user();

        return [
            Stat::make('Presensi', Attendance::query()->where('user_id', $user?->id)->count())
                ->description('Total riwayat kehadiran Anda')
                ->color('success'),
            Stat::make('Agenda Mendatang', EventAgenda::query()->published()->upcoming()->count())
                ->description('Agenda yang akan datang')
                ->color('warning'),
            Stat::make('Berita Saya', NewsPost::query()->where('author_id', $user?->id)->count())
                ->description('Jumlah berita yang Anda buat')
                ->color('primary'),
        ];
    }
}
