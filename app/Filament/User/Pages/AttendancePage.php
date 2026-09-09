<?php

namespace App\Filament\User\Pages;

use App\Filament\Concerns\YearFilterable;
use App\Models\Attendance;
use App\Models\EventAgenda;
use BackedEnum;
use Filament\Actions\ViewAction;
use Filament\Facades\Filament;
use Filament\Pages\Page;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
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
        $this->createExpiredAttendancesForCurrentUser();

        return $this->applyYearFilter(
            EventAgenda::query()
                ->published()
                ->with(['attendances' => fn ($query) => $query->where('user_id', Filament::auth()->id())])
                ->orderBy('starts_at'),
            'starts_at',
        );
    }

    private function createExpiredAttendancesForCurrentUser(): void
    {
        $userId = Filament::auth()->id();

        if (! $userId) {
            return;
        }

        $expiredAgendas = EventAgenda::published()
            ->whereNotNull('ends_at')
            ->where('ends_at', '<', now())
            ->whereDoesntHave('attendances', fn ($query) => $query->where('user_id', $userId))
            ->get();

        foreach ($expiredAgendas as $agenda) {
            Attendance::firstOrCreate(
                [
                    'user_id' => $userId,
                    'event_agenda_id' => $agenda->id,
                ],
                [
                    'status' => 'tidak',
                    'scanned_at' => null,
                    'method' => 'auto',
                    'notes' => 'Presensi otomatis ditandai tidak hadir karena melewati batas waktu.',
                ],
            );
        }
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('name')
                ->label('Presensi')
                ->searchable(),
            BadgeColumn::make('attendances.0.status')
                ->label('Status Presensi')
                ->colors([
                    'success' => 'hadir',
                    'warning' => 'terlambat',
                    'secondary' => 'izin',
                    'danger' => ['alpha', 'tidak'],
                ])
                ->formatStateUsing(fn (?string $state): string => match ($state) {
                    'hadir' => 'Hadir',
                    'terlambat' => 'Terlambat',
                    'izin' => 'Izin',
                    'tidak' => 'Tidak Hadir',
                    'alpha' => 'Tidak',
                    default => 'Belum Presensi',
                }),
            TextColumn::make('waktu_scan')
                ->label('Waktu Scan')
                ->getStateUsing(fn (EventAgenda $record) => $record->attendances->first()?->scanned_at)
                ->dateTime('d F Y H:i:s'),
            TextColumn::make('bukti')
                ->label('Bukti')
                ->getStateUsing(fn (EventAgenda $record) => $record->attendances->first()?->photoUrl)
                ->formatStateUsing(fn (?string $url) => $url ? "<img src=\"{$url}\" style=\"height:48px;width:48px;object-fit:cover;border-radius:8px;\" />" : '-')
                ->html(),
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
                ->disabled(fn (EventAgenda $record): bool => $record->attendances->isNotEmpty()
                    || ($record->ends_at !== null && now()->greaterThan($record->ends_at)))
                ->modalContent(fn (EventAgenda $record) => view('filament.user.partials.presensi-gps-modal', ['eventAgenda' => $record])),
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

}
