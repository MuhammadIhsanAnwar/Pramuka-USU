<?php

namespace App\Filament\User\Pages;

use App\Models\Attendance;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Support\Facades\Auth;
use BackedEnum;
use UnitEnum;

class AttendancePage extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $title = 'Presensi';
    protected static ?string $slug = 'presensi';
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-qr-code';
    protected static ?string $navigationLabel = 'Presensi';
    protected static string | UnitEnum | null $navigationGroup = 'User';

    protected function getTableQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return Attendance::query()
            ->where('user_id', Auth::id())
            ->with('agenda')
            ->latest('scanned_at');
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('agenda.name')->label('Agenda')->searchable()->sortable(),
            TextColumn::make('scanned_at')->label('Waktu')->dateTime('d M Y H:i')->sortable(),
            BadgeColumn::make('status')->label('Status')->colors(['success' => 'hadir', 'danger' => 'absent']),
        ];
    }

    protected function getTableActions(): array
    {
        return [];
    }

    protected function getTableBulkActions(): array
    {
        return [];
    }
}
