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
                TextInput::make('setting_group')
                    ->label('Grup')
                    ->maxLength(255),
                Select::make('setting_type')
                    ->label('Tipe')
                    ->options([
                        'text' => 'Teks',
                        'textarea' => 'Textarea',
                        'image' => 'Gambar',
                        'video' => 'Video',
                        'number' => 'Angka',
                        'toggle' => 'Tombol',
                    ])
                    ->required()
                    ->default('text'),
                TextInput::make('setting_value')
                    ->label('Nilai')
                    ->visible(fn (callable $get): bool => $get('setting_type') === 'text' || $get('setting_type') === 'number')
                    ->columnSpanFull(),
                Textarea::make('setting_value')
                    ->label('Nilai')
                    ->visible(fn (callable $get): bool => $get('setting_type') === 'textarea')
                    ->columnSpanFull()
                    ->rows(4),
                FileUpload::make('setting_value')
                    ->label('Nilai')
                    ->directory('beranda')
                    ->disk('public')
                    ->visibility('public')
                    ->acceptedFileTypes(fn (callable $get): array => $get('setting_type') === 'video'
                        ? ['video/mp4', 'video/webm', 'video/ogg']
                        : ['image/jpeg', 'image/png', 'image/webp'])
                    ->visible(fn (callable $get): bool => in_array($get('setting_type'), ['image', 'video'], true))
                    ->columnSpanFull()
                    ->maxSize(10240),
                Toggle::make('setting_value')
                    ->label('Nilai')
                    ->visible(fn (callable $get): bool => $get('setting_type') === 'toggle')
                    ->columnSpanFull(),
                Toggle::make('is_public')
                    ->label('Publik')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        SiteSetting::ensureDefaultSettings();

        return $table
            ->columns([
                TextColumn::make('label')->searchable(),
                TextColumn::make('setting_group')->label('Grup')->badge()->toggleable(),
                TextColumn::make('setting_type')
                    ->label('Tipe')
                    ->badge()
                    ->colors([
                        'gray' => 'text',
                        'info' => 'textarea',
                        'success' => 'image',
                        'warning' => 'number',
                        'primary' => 'toggle',
                    ])
                    ->formatStateUsing(fn (?string $state): ?string => match ($state) {
                        'text' => 'Teks',
                        'textarea' => 'Textarea',
                        'image' => 'Gambar',
                        'number' => 'Angka',
                        'toggle' => 'Tombol',
                        default => $state,
                    }),
                ToggleColumn::make('is_public'),
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
            'index' => Pages\ListSiteSettings::route('/'),
            'create' => Pages\CreateSiteSetting::route('/create'),
            'edit' => Pages\EditSiteSetting::route('/{record}/edit'),
        ];
    }
}