<?php

namespace App\Filament\User\Widgets;

use App\Models\Attendance;
use App\Models\EventAgenda;
use App\Models\NewsPost;
use Filament\Widgets\Widget;

class UserDashboardSummaryWidget extends Widget
{
    protected string $view = 'filament.widgets.user-dashboard-summary';

    protected int|string|array $columnSpan = 'full';

    protected function getViewData(): array
    {
        $user = auth()->user();

        return [
            'user' => $user,
            'attendanceHistory' => Attendance::query()
                ->with('agenda')
                ->where('user_id', $user->id)
                ->latest('scanned_at')
                ->take(5)
                ->get(),
            'myNews' => NewsPost::query()
                ->where('author_id', $user->id)
                ->latest()
                ->take(5)
                ->get(),
            'upcomingAgendas' => EventAgenda::query()
                ->published()
                ->upcoming()
                ->orderBy('starts_at')
                ->take(5)
                ->get(),
        ];
    }
}