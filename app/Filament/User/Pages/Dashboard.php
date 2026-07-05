<?php

namespace App\Filament\User\Pages;

use App\Filament\User\Widgets\UserDashboardSummaryWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Dashboard User';

    public function getWidgets(): array
    {
        return [
            UserDashboardSummaryWidget::class,
        ];
    }

    public function getColumns(): int|array
    {
        return 1;
    }
}