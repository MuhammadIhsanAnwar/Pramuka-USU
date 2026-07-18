<?php

namespace App\Filament\User\Widgets;

use App\Models\Attendance;
use App\Models\EventAgenda;
use App\Models\NewsPost;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class UserDashboardSummaryWidget extends Widget
{
    protected string $view = 'filament.widgets.user-dashboard-summary';

    protected int|string|array $columnSpan = 'full';

    protected function getViewData(): array
    {
        $user = Auth::user();

        return [
            'user' => $user,
            'attendanceCount' => Attendance::query()
                ->where('user_id', $user->id)
                ->count(),
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
            'newsCount' => NewsPost::query()
                ->where('author_id', $user->id)
                ->count(),
            'upcomingAgendaCount' => EventAgenda::query()
                ->published()
                ->upcoming()
                ->count(),
            'upcomingAgendas' => EventAgenda::query()
                ->published()
                ->upcoming()
                ->orderBy('starts_at')
                ->take(5)
                ->get(),
        ];
    }
}