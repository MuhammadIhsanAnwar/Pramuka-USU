<?php

namespace App\Filament\User\Pages;

use App\Models\IncomingLetter;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use BackedEnum;
use UnitEnum;

class IncomingLettersPage extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $title = 'Surat Masuk';
    protected static ?string $slug = 'surat-masuk';
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-envelope';
    protected static ?string $navigationLabel = 'Surat Masuk';
    protected static string | UnitEnum | null $navigationGroup = 'User';

    protected function getTableQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return IncomingLetter::query()->latest('letter_date');
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('letter_date')->label('Tanggal')->date()->sortable(),
            TextColumn::make('sender')->label('Pengirim')->searchable(),
            TextColumn::make('letter_number')->label('Nomor Surat')->searchable(),
            TextColumn::make('subject')->label('Perihal')->limit(30),
            BadgeColumn::make('classification')->label('Klasifikasi')->colors(['primary' => 'Surat Masuk']),
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
