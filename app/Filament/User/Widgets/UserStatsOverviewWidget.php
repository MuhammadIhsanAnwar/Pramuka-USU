<?php

namespace App\Filament\User\Widgets;

use App\Enums\RoleName;
use App\Enums\UserKind;
use App\Models\Attendance;
use App\Models\EventAgenda;
use App\Models\NewsPost;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class UserStatsOverviewWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $user = Auth::user();

        return [
            Stat::make('Jumlah User Aktif', User::query()
                ->where('is_active', true)
                ->whereHas('roles', fn ($query) => $query->where('name', RoleName::User->value))
                ->count())
                ->color('success'),
            Stat::make('Jumlah Purna', User::query()->where('jenis_user', UserKind::Purna->value)->count())
                ->color('warning'),
            Stat::make('Jumlah Pembina 08-137', User::query()->where('satuan', 'Gugus Depan Gerakan Pramuka Kota Medan 08-137')->count())
                ->color('primary'),
            Stat::make('Jumlah Pembina 08-138', User::query()->where('satuan', 'Gugus Depan Gerakan Pramuka Kota Medan 08-138')->count())
                ->color('primary'),
            Stat::make('Jumlah Anggota Racana Soetan Koemala Pontas', User::query()->whereNotIn('jenis_user', [UserKind::Purna->value, UserKind::Tamu->value])->where('satuan', 'Racana Soetan Koemala Pontas')->count())
                ->color('secondary'),
            Stat::make('Jumlah Anggota Racana Rasuna Said', User::query()->whereNotIn('jenis_user', [UserKind::Purna->value, UserKind::Tamu->value])->where('satuan', 'Racana Rasuna Said')->count())
                ->color('secondary'),
            Stat::make('Jumlah Anggota Ambalan Soetan Koemala Pontas', User::query()->whereNotIn('jenis_user', [UserKind::Purna->value, UserKind::Tamu->value])->where('satuan', 'Ambalan Soetan Koemala Pontas')->count())
                ->color('secondary'),
            Stat::make('Jumlah Anggota Ambalan Rasuna Said', User::query()->whereNotIn('jenis_user', [UserKind::Purna->value, UserKind::Tamu->value])->where('satuan', 'Ambalan Rasuna Said')->count())
                ->color('secondary'),
            Stat::make('Jumlah Tamu', User::query()->where('jenis_user', UserKind::Tamu->value)->count())
                ->color('danger'),
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
