<?php

namespace App\Filament\User\Widgets;

use Filament\Widgets\Widget;

class UserDashboardHeaderWidget extends Widget
{
    protected string $view = 'filament.widgets.user-dashboard-header-widget';

    protected int | string | array $columnSpan = 'full';

    public function getViewData(): array
    {
        return [
            'title' => 'Selamat Datang di Dashboard User',
        ];
    }
}
