<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Widgets\AdminStatsOverview;
use App\Filament\Admin\Widgets\AttendanceChartWidget;
use App\Filament\Admin\Widgets\NewsMonthlyChartWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    public function getWidgets(): array
    {
        return [
            AdminStatsOverview::class,
            AttendanceChartWidget::class,
            NewsMonthlyChartWidget::class,
        ];
    }

    public function getColumns(): int|array
    {
        return 2;
    }
}