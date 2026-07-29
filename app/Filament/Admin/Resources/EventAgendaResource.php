<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\EventAgendaResource\Pages;
use App\Models\EventAgenda;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use BackedEnum;
use UnitEnum;

class EventAgendaResource extends Resource
{
    protected static ?string $model = EventAgenda::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-calendar-days';

    protected static string|UnitEnum|null $navigationGroup = 'Manajemen Kegiatan';

    protected static ?string $navigationLabel = 'Agenda';

    public static function getPluralModelLabel(): string
    {
        return 'Agenda';
    }

    public static function getSingularModelLabel(): string
    {
        return 'Agenda';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('name')
                    ->label('Nama')
                    ->required()
                    ->maxLength(255),
                TextInput::make('location')
                    ->label('Lokasi')
                    ->required()
                    ->maxLength(255),
                Select::make('type')
                    ->label('Jenis Kegiatan')
                    ->options([
                        'internal' => 'Internal',
                        'external' => 'Eksternal',
                    ])
                    ->required()
                    ->default('internal'),
                TextInput::make('organizer')
                    ->label('Penyelenggara')
                    ->maxLength(255)
                    ->required(fn ($get) => $get('type') === 'external'),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                        'archived' => 'Archived',
                    ])
                    ->required()
                    ->default('draft'),
                DateTimePicker::make('starts_at')
                    ->label('Mulai')
                    ->required(),
                DateTimePicker::make('ends_at')
                    ->label('Selesai'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Jenis Kegiatan')
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'internal' => 'Internal',
                        'external' => 'Eksternal',
                        default => $state ?? '-',
                    })
                    ->sortable(),
                TextColumn::make('organizer')
                    ->label('Penyelenggara')
                    ->searchable(),
                TextColumn::make('location')
                    ->label('Lokasi')
                    ->searchable(),
                BadgeColumn::make('status')
                    ->colors([
                        'gray' => 'draft',
                        'success' => 'published',
                        'warning' => 'archived',
                    ]),
                TextColumn::make('starts_at')
                    ->label('Mulai')
                    ->dateTime('d F Y H:i:s')
                    ->sortable(),
                TextColumn::make('ends_at')
                    ->label('Waktu Selesai')
                    ->dateTime('d F Y H:i:s')
                    ->sortable(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEventAgendas::route('/'),
            'create' => Pages\CreateEventAgenda::route('/create'),
            'edit' => Pages\EditEventAgenda::route('/{record}/edit'),
        ];
    }
}