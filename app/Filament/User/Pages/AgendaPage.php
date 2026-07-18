<?php

namespace App\Filament\User\Pages;

use App\Models\EventAgenda;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use BackedEnum;
use UnitEnum;

class AgendaPage extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $title = 'Agenda';
    protected static ?string $slug = 'agenda';
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationLabel = 'Agenda';
    protected static string | UnitEnum | null $navigationGroup = 'User';

    protected function getTableQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return EventAgenda::query()->published()->orderBy('starts_at');
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('name')->label('Nama Agenda')->searchable()->sortable(),
            TextColumn::make('starts_at')->label('Waktu')->dateTime('d M Y H:i')->sortable(),
            TextColumn::make('location')->label('Lokasi'),
            BadgeColumn::make('status')->label('Status')->colors(['success' => 'published', 'warning' => 'draft', 'secondary' => 'pending']),
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
