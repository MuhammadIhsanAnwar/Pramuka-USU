<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PresensiSessionResource\Pages;
use App\Models\PresensiSession;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Html;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use BackedEnum;
use UnitEnum;

class PresensiSessionResource extends Resource
{
    protected static ?string $model = PresensiSession::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-calendar-days';

    protected static string|UnitEnum|null $navigationGroup = 'Manajemen Kegiatan';

    protected static ?string $navigationLabel = 'Presensi';

    public static function getPluralModelLabel(): string
    {
        return 'Presensi';
    }

    public static function getSingularModelLabel(): string
    {
        return 'Presensi';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Section::make('Detail Presensi')->schema([
                    Select::make('event_agenda_id')
                        ->label('Agenda')
                        ->relationship('agenda', 'name')
                        ->required()
                        ->searchable()
                        ->preload(),
                    TextInput::make('name')
                        ->label('Nama Presensi')
                        ->required()
                        ->maxLength(255),
                    RichEditor::make('description')
                        ->label('Deskripsi')
                        ->toolbarButtons(['bold', 'italic', 'bulletList', 'orderedList', 'link'])
                        ->nullable(),
                    TextInput::make('location')
                        ->label('Lokasi Presensi')
                        ->maxLength(255)
                        ->nullable(),
                    DateTimePicker::make('starts_at')
                        ->label('Mulai Presensi')
                        ->required(),
                    DateTimePicker::make('ends_at')
                        ->label('Batas Presensi')
                        ->required(),
                    TextInput::make('radius')
                        ->label('Radius (meter)')
                        ->numeric()
                        ->default(500)
                        ->required(),
                    Select::make('status')
                        ->label('Status')
                        ->options([
                            'draft' => 'Draft',
                            'published' => 'Published',
                            'archived' => 'Archived',
                        ])
                        ->default('draft')
                        ->required(),
                ])->columnSpan(2),
                Section::make('Token QR Presensi')->schema([
                    TextInput::make('qr_token')
                        ->label('Token QR Presensi')
                        ->disabled()
                        ->nullable(),
                    Html::make(fn (): string => view('filament.admin.presensi-session-qr-placeholder')->render())
                        ->columnSpanFull(),
                ])->columnSpan(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nama Presensi')->searchable()->sortable(),
                TextColumn::make('agenda.name')->label('Agenda')->searchable()->sortable(),
                TextColumn::make('starts_at')->label('Mulai')->dateTime('d F Y H:i')->sortable(),
                TextColumn::make('ends_at')->label('Batas')->dateTime('d F Y H:i')->sortable(),
                BadgeColumn::make('status')
                    ->colors(['gray' => 'draft', 'success' => 'published', 'warning' => 'archived']),
                TextColumn::make('radius')->label('Radius'),
            ])
            ->actions([
                ViewAction::make(),
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
            'index' => Pages\ListPresensiSessions::route('/'),
            'create' => Pages\CreatePresensiSession::route('/create'),
            'edit' => Pages\EditPresensiSession::route('/{record}/edit'),
            'view' => Pages\ViewPresensiSession::route('/{record}/view'),
        ];
    }
}
