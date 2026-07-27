<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SiteSettingResource\Pages;
use App\Models\SiteSetting;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use BackedEnum;
use UnitEnum;

class SiteSettingResource extends Resource
{
    protected static ?string $model = SiteSetting::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string|UnitEnum|null $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Pengaturan Website';

    public static function getPluralModelLabel(): string
    {
        return 'Pengaturan Website';
    }

    public static function getSingularModelLabel(): string
    {
        return 'Pengaturan Website';
    }

    public static function form(Schema $schema): Schema
    {
        SiteSetting::ensureDefaultSettings();

        return $schema
            ->columns(2)
            ->components([
                TextInput::make('setting_key')
                    ->label('Kunci')
                    ->hidden()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->default(fn (callable $get): string => Str::slug($get('label') ?? ''))
                    ->maxLength(255),
                TextInput::make('label')
                    ->label('Label')
                    ->required()
                    ->maxLength(255),
                TextInput::make('setting_value')
                    ->label('Upload')
                    ->visible(fn (callable $get, ?SiteSetting $record): bool => ($get('setting_type') === 'text' || $get('setting_type') === 'number') && ($record?->setting_key !== 'maintenance_mode'))
                    ->columnSpanFull(),
                Textarea::make('setting_value')
                    ->label('Upload')
                    ->visible(fn (callable $get, ?SiteSetting $record): bool => $get('setting_type') === 'textarea' && ($record?->setting_key !== 'maintenance_mode'))
                    ->columnSpanFull()
                    ->rows(4),
                FileUpload::make('setting_value')
                    ->label('Upload')
                    ->directory('beranda')
                    ->disk('public')
                    ->visibility('public')
                    ->acceptedFileTypes(fn (callable $get): array => $get('setting_type') === 'video'
                        ? ['video/mp4', 'video/webm', 'video/ogg']
                        : ['image/jpeg', 'image/png', 'image/webp'])
                    ->visible(fn (callable $get, ?SiteSetting $record): bool => in_array($get('setting_type'), ['image', 'video'], true) && ($record?->setting_key !== 'maintenance_mode'))
                    ->columnSpanFull()
                    ->maxSize(10240),
                Toggle::make('setting_value')
                    ->label('Upload')
                    ->visible(fn (callable $get, ?SiteSetting $record): bool => $get('setting_type') === 'toggle' && ($record?->setting_key !== 'maintenance_mode'))
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        SiteSetting::ensureDefaultSettings();

        return $table
            ->columns([
                TextColumn::make('label')->searchable(),
                ToggleColumn::make('active')
                    ->label('Aktif')
                    ->getStateUsing(fn ($record): bool =>
                        $record->setting_key === 'maintenance_mode'
                            ? (bool) ($record->setting_value[0] ?? $record->setting_value)
                            : (bool) $record->is_public
                    )
                    ->updateStateUsing(function ($state, ToggleColumn $column) {
                        $record = $column->getRecord();

                        if ($record->setting_key === 'maintenance_mode') {
                            $record->setting_value = [$state];
                        } else {
                            $record->is_public = (bool) $state;
                        }

                        $record->save();

                        return $state;
                    }),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSiteSettings::route('/'),
            'edit' => Pages\EditSiteSetting::route('/{record}/edit'),
        ];
    }
}