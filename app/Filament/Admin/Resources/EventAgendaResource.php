<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\EventAgendaResource\Pages;
use App\Models\EventAgenda;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
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
                TextInput::make('slug')
                    ->label('Slug')
                    ->disabled()
                    ->dehydrated(false)
                    ->helperText('Slug dibuat otomatis dari nama.'),
                TextInput::make('location')
                    ->label('Lokasi')
                    ->required()
                    ->maxLength(255),
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
                FileUpload::make('poster_path')
                    ->label('Poster')
                    ->image()
                    ->directory('agendas/posters')
                    ->visibility('public')
                    ->maxSize(4096),
                RichEditor::make('description')
                    ->label('Deskripsi')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('poster_path')
                    ->label('Poster')
                    ->square(),
                ImageColumn::make('qr_code_path')
                    ->label('QR Code')
                    ->square()
                    ->toggleable(),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('location')
                    ->searchable(),
                BadgeColumn::make('status')
                    ->colors([
                        'gray' => 'draft',
                        'success' => 'published',
                        'warning' => 'archived',
                    ]),
                TextColumn::make('starts_at')
                    ->label('Mulai')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_by.name')
                    ->label('Dibuat Oleh')
                    ->toggleable(),
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