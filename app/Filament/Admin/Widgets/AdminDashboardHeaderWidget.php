<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\Widget;

class AdminDashboardHeaderWidget extends Widget
{
    protected string $view = 'filament.widgets.admin-dashboard-header-widget';

    protected int | string | array $columnSpan = 'full';

    public function getViewData(): array
    {
        return [
            'title' => 'Selamat Datang di Dashboard Admin',
        ];
    }
}

