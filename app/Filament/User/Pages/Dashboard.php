<?php

namespace App\Filament\User\Pages;

use App\Filament\User\Widgets\UserDashboardSummaryWidget;
use App\Filament\User\Widgets\UserStatsOverviewWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Dashboard User';

    public function getWidgets(): array
    {
        return [
            UserDashboardSummaryWidget::class,
            UserStatsOverviewWidget::class,
        ];
    }

    public function getColumns(): int|array
    {
        return 2;
    }
}