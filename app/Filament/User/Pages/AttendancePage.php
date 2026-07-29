<?php

namespace App\Filament\User\Pages;

use App\Filament\Concerns\YearFilterable;
use App\Models\Attendance;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\ViewAction;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class AttendancePage extends Page implements HasTable
{
    use InteractsWithTable;
    use YearFilterable;

    protected function getTableHeader(): View | Htmlable | null
    {
        return $this->getYearFilterHeader();
    }

    protected function getTableQuery(): Builder | Relation
    {
        return $this->applyYearFilter(
            Attendance::query()
                ->with('agenda')
                ->where('user_id', Auth::id())
                ->latest('scanned_at'),
            'scanned_at',
        );
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('agenda.name')
                ->label('Agenda')
                ->searchable(),
            BadgeColumn::make('status')
                ->label('Status Presensi')
                ->colors([
                    'success' => 'hadir',
                    'danger' => 'terlambat',
                    'secondary' => 'tidak',
                ])
                ->formatStateUsing(fn (?string $state): string => match ($state) {
                    'hadir' => 'Hadir',
                    'terlambat' => 'Terlambat',
                    default => 'Tidak',
                }),
            TextColumn::make('scanned_at')
                ->label('Waktu Scan')
                ->dateTime('d F Y H:i:s')
                ->sortable(),
            TextColumn::make('starts_at')
                ->label('Mulai Presensi')
                ->dateTime('d F Y H:i:s')
                ->sortable(),
            TextColumn::make('ends_at')
                ->label('Batas Presensi')
                ->dateTime('d F Y H:i:s')
                ->sortable(),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            ViewAction::make('lakukan_presensi')
                ->label('Lakukan Presensi')
                ->modalHeading('Lakukan Presensi')
                ->modalWidth('md')
                ->modalContent(fn ($record) => view('filament.user.partials.presensi-gps-modal', ['eventAgenda' => $record->agenda])),
        ];
    }

    protected function getTableBulkActions(): array
    {
        return [];
    }

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
                ->with('agenda')
                ->where('user_id', Auth::id())
                ->latest('scanned_at')
                ->get(),
        ];
    }
}
