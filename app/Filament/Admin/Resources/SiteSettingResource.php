<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SiteSettingResource\Pages;
use App\Models\SiteSetting;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use BackedEnum;
use UnitEnum;

class SiteSettingResource extends Resource
{
    protected static ?string $model = SiteSetting::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string|UnitEnum|null $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Website';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('setting_key')
                    ->label('Key')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                TextInput::make('label')
                    ->label('Label')
                    ->required()
                    ->maxLength(255),
                TextInput::make('setting_group')
                    ->label('Group')
                    ->maxLength(255),
                Select::make('setting_type')
                    ->label('Tipe')
                    ->options([
                        'text' => 'Text',
                        'textarea' => 'Textarea',
                        'image' => 'Image',
                        'number' => 'Number',
                    ])
                    ->required()
                    ->default('text'),
                Textarea::make('setting_value')
                    ->label('Nilai')
                    ->columnSpanFull()
                    ->rows(4),
                Toggle::make('is_public')
                    ->label('Publik')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('setting_key')->searchable()->sortable(),
                TextColumn::make('label')->searchable(),
                TextColumn::make('setting_group')->badge()->toggleable(),
                BadgeColumn::make('setting_type')->colors([
                    'gray' => 'text',
                    'info' => 'textarea',
                    'success' => 'image',
                    'warning' => 'number',
                ]),
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