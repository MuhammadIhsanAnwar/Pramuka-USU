<?php

namespace App\Filament\User\Pages;

use App\Models\Attendance;
use BackedEnum;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class AttendancePage extends Page
{
    protected static ?string $title = 'Presensi';
    protected static ?string $slug = 'presensi';
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-qr-code';
    protected static ?string $navigationLabel = 'Presensi';
    protected static string | UnitEnum | null $navigationGroup = 'User';
    protected string $view = 'filament.user.pages.attendance';

    public function getViewData(): array
    {
        return [
            'attendances' => Attendance::query()
                ->where('user_id', Auth::id())
                ->with('agenda')
                ->latest('scanned_at')
                ->get(),
        ];
    }
}
