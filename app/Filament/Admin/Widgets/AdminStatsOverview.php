<?php

namespace App\Filament\Admin\Widgets;

use App\Enums\RoleName;
use App\Enums\UserKind;
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
        $counts = [
            'users' => User::query()
                ->where('is_active', true)
                ->whereDoesntHave('roles', fn ($query) => $query->where('name', RoleName::Admin->value))
                ->count(),
            'purna' => User::query()->where('jenis_user', UserKind::Purna->value)->count(),
            'pembina_08_137' => User::query()->where('satuan', 'Gugus Depan Gerakan Pramuka Kota Medan 08-137')->count(),
            'pembia_08_138' => User::query()->where('satuan', 'Gugus Depan Gerakan Pramuka Kota Medan 08-138')->count(),
            'anggota_racana_soetan' => User::query()
                ->whereNotIn('jenis_user', [UserKind::Purna->value, UserKind::Tamu->value])
                ->where('satuan', 'Racana Soetan Koemala Pontas')
                ->count(),
            'anggota_racana_rasuna' => User::query()
                ->whereNotIn('jenis_user', [UserKind::Purna->value, UserKind::Tamu->value])
                ->where('satuan', 'Racana Rasuna Said')
                ->count(),
            'anggota_ambalan_soetan' => User::query()
                ->whereNotIn('jenis_user', [UserKind::Purna->value, UserKind::Tamu->value])
                ->where('satuan', 'Ambalan Soetan Koemala Pontas')
                ->count(),
            'anggota_ambalan_rasuna' => User::query()
                ->whereNotIn('jenis_user', [UserKind::Purna->value, UserKind::Tamu->value])
                ->where('satuan', 'Ambalan Rasuna Said')
                ->count(),
            'tamu' => User::query()->where('jenis_user', UserKind::Tamu->value)->count(),
        ];

        return [
            Stat::make('Jumlah User Aktif', $counts['users']),
            Stat::make('Jumlah Purna', $counts['purna']),
            Stat::make('Jumlah Pembina 08-137', $counts['pembina_08_137']),
            Stat::make('Jumlah Pembia 08-138', $counts['pembia_08_138']),
            Stat::make('Jumlah Anggota Racana Soetan Koemala Pontas', $counts['anggota_racana_soetan']),
            Stat::make('Jumlah Anggota Racana Rasuna Said', $counts['anggota_racana_rasuna']),
            Stat::make('Jumlah Anggota Ambalan Soetan Koemala Pontas', $counts['anggota_ambalan_soetan']),
            Stat::make('Jumlah Anggota Ambalan Rasuna Said', $counts['anggota_ambalan_rasuna']),
            Stat::make('Jumlah Tamu', $counts['tamu']),
        ];
    }
}