<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\AttendanceResource\Pages;
use App\Models\Attendance;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Html;
use Illuminate\Support\Facades\Auth;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use BackedEnum;
use UnitEnum;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-qr-code';

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
                TextInput::make('user_id')
                    ->hidden()
                    ->default(fn () => Auth::id())
                    ->required(),
                Select::make('event_agenda_id')
                    ->label('Agenda')
                    ->relationship('agenda', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                DateTimePicker::make('starts_at')
                    ->label('Mulai Presensi')
                    ->required()
                    ->default(now()),
                DateTimePicker::make('ends_at')
                    ->label('Batas Presensi')
                    ->required()
                    ->default(fn () => now()->addHour()),
                TextInput::make('latitude')
                    ->hidden()
                    ->required(),
                TextInput::make('longitude')
                    ->hidden()
                    ->required(),
                Html::make(fn (): string => view('filament.admin.attendance-location-picker')->render())
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('agenda.name')->label('Agenda')->searchable(),
                TextColumn::make('status')
                    ->label('Status Presensi')
                    ->formatStateUsing(fn (?string $state, $record): string => $record?->starts_at && $record?->ends_at && now()->between($record->starts_at, $record->ends_at)
                        ? 'Aktif'
                        : 'Tidak'),
                TextColumn::make('starts_at')->label('Mulai Presensi')->dateTime('d F Y H:i:s')->sortable(),
                TextColumn::make('ends_at')->label('Batas Presensi')->dateTime('d F Y H:i:s')->sortable(),
                TextColumn::make('latitude'),
                TextColumn::make('longitude'),
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
            'index' => Pages\ListAttendances::route('/'),
            'create' => Pages\CreateAttendance::route('/create'),
            'edit' => Pages\EditAttendance::route('/{record}/edit'),
            'view' => Pages\ViewAttendance::route('/{record}/view'),
        ];
    }
}