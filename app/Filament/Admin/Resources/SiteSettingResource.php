<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SiteSettingResource\Pages;
use App\Models\SiteSetting;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
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
                Repeater::make('setting_value')
                    ->label('Logo Beranda Bawah Video')
                    ->visible(fn (callable $get, ?SiteSetting $record): bool => $get('setting_key') === 'home_brand_logos' || $record?->setting_key === 'home_brand_logos')
                    ->columnSpanFull()
                    ->schema([
                        FileUpload::make('path')
                            ->label('Upload Logo')
                            ->image()
                            ->directory('home_brand_logos')
                            ->disk('public_storage')
                            ->visibility('public')
                            ->maxSize(5120)
                            ->imageCropAspectRatio('1:1'),
                    ])
                    ->createItemButtonLabel('Tambah Logo')
                    ->columns(1)
                    ->reorderableWithButtons()
                    ->deleteAction(function (Action $action): Action {
                        return $action
                            ->label('Hapus logo')
                            ->icon('heroicon-o-trash')
                            ->color('danger');
                    })
                    ->moveUpAction(function (Action $action): Action {
                        return $action
                            ->label('Pindahkan naik')
                            ->icon('heroicon-o-arrow-up')
                            ->color('gray');
                    })
                    ->moveDownAction(function (Action $action): Action {
                        return $action
                            ->label('Pindahkan turun')
                            ->icon('heroicon-o-arrow-down')
                            ->color('gray');
                    })
                    ->default([])
                    ->afterStateHydrated(function (mixed $state, ?SiteSetting $record): array {
                        if (is_bool($state) || $state === null) {
                            return [];
                        }

                        if (is_string($state)) {
                            $decoded = json_decode($state, true);

                            if (json_last_error() === JSON_ERROR_NONE) {
                                $state = $decoded;
                            }
                        }

                        if (! is_array($state)) {
                            return [];
                        }

                        return collect($state)
                            ->map(function ($item) {
                                if (is_array($item)) {
                                    return $item;
                                }

                                if (is_string($item)) {
                                    return ['path' => $item];
                                }

                                return null;
                            })
                            ->filter()
                            ->values()
                            ->all();
                    }),
                TextInput::make('setting_value')
                    ->label('Upload')
                    ->visible(fn (callable $get, ?SiteSetting $record): bool => in_array($get('setting_type'), ['image', 'video'], true) && ($record?->setting_key !== 'maintenance_mode') && ($get('setting_key') !== 'home_brand_logos' && $record?->setting_key !== 'home_brand_logos'))
                    ->columnSpanFull()
                    ->maxLength(255),
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

    public static function normalizeHomeBrandLogosForForm(mixed $value): array
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $value = $decoded;
            }
        }

        if (! is_array($value)) {
            return [];
        }

        return collect($value)
            ->map(function ($item): ?array {
                $path = self::extractLogoPath($item);

                if ($path === null || $path === '') {
                    return null;
                }

                return ['path' => (string) $path];
            })
            ->filter()
            ->values()
            ->all();
    }

    protected static function extractLogoPath(mixed $value): ?string
    {
        if (is_string($value)) {
            return $value;
        }

        if (is_array($value)) {
            foreach (['path', 'file', 'url', 'value'] as $key) {
                if (array_key_exists($key, $value)) {
                    $extracted = self::extractLogoPath($value[$key]);

                    if ($extracted !== null) {
                        return $extracted;
                    }
                }
            }

            if (array_key_exists(0, $value)) {
                return self::extractLogoPath($value[0]);
            }
        }

        return null;
    }
}