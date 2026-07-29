<?php

namespace App\Filament\User\Pages;

use App\Filament\Concerns\YearFilterable;
use App\Models\EventAgenda;
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
use UnitEnum;

class AgendaPage extends Page implements HasTable
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
            EventAgenda::query()
                ->published()
                ->orderBy('starts_at'),
            'starts_at',
        );
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('name')
                ->label('Nama Kegiatan')
                ->searchable(),
            TextColumn::make('type')
                ->label('Jenis Kegiatan')
                ->formatStateUsing(fn (?string $state): string => match ($state) {
                    'internal' => 'Internal',
                    'external' => 'Eksternal',
                    default => '-',
                })
                ->sortable(),
            TextColumn::make('organizer')
                ->label('Penyelenggara')
                ->searchable(),
            TextColumn::make('location')
                ->label('Lokasi')
                ->searchable(),
            TextColumn::make('starts_at')
                ->label('Dimulai')
                ->dateTime('d F Y H:i:s')
                ->sortable(),
            TextColumn::make('ends_at')
                ->label('Waktu Selesai')
                ->dateTime('d F Y H:i:s')
                ->sortable(),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            ViewAction::make()
                ->label('Detail')
                ->modalHeading('')
                ->modalSubheading('')
                ->modalWidth('lg')
                ->modalContent(fn ($record) => view('filament.user.event-agenda-view', ['eventAgenda' => $record])),
        ];
    }

    protected function getTableBulkActions(): array
    {
        return [];
    }

    protected static ?string $title = 'Agenda';
    protected static ?string $slug = 'agenda';
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationLabel = 'Agenda';
    protected static string | UnitEnum | null $navigationGroup = 'User';
    protected string $view = 'filament.user.pages.agenda';

    public function getViewData(): array
    {
        return [
            'agendas' => EventAgenda::query()
                ->published()
                ->orderBy('starts_at')
                ->get(),
        ];
    }
}
