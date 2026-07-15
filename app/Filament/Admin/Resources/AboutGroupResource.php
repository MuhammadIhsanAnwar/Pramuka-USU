<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\AboutGroupResource\Pages;
use App\Models\AboutGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use BackedEnum;
use UnitEnum;

class AboutGroupResource extends Resource
{
    protected static ?string $model = AboutGroup::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-group';

    protected static string|UnitEnum|null $navigationGroup = 'Tentang Kami';

    protected static ?string $navigationLabel = 'Grup Tim';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('name')
                    ->label('Nama Grup')
                    ->required()
                    ->maxLength(255),
                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->maxLength(150)
                    ->unique(ignoreRecord: true)
                    ->helperText('Slug digunakan untuk identifikasi tab menu.'),
                Textarea::make('description')
                    ->label('Deskripsi Grup')
                    ->rows(4)
                    ->columnSpanFull(),
                TextInput::make('order')
                    ->label('Urutan')
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nama')->searchable()->sortable(),
                TextColumn::make('slug')->label('Slug')->searchable(),
                TextColumn::make('description')->label('Deskripsi')->limit(80),
                TextColumn::make('order')->label('Urutan')->sortable(),
                ToggleColumn::make('is_active')->label('Aktif'),
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
            'index' => Pages\ListAboutGroups::route('/'),
            'create' => Pages\CreateAboutGroup::route('/create'),
            'edit' => Pages\EditAboutGroup::route('/{record}/edit'),
        ];
    }
}
