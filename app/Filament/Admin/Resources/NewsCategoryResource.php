<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\NewsCategoryResource\Pages;
use App\Models\NewsCategory;
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

class NewsCategoryResource extends Resource
{
    protected static ?string $model = NewsCategory::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-tag';

    protected static string|UnitEnum|null $navigationGroup = 'Manajemen Konten';

    protected static ?string $navigationLabel = 'Kategori Berita';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
            TextInput::make('name')
                ->label('Nama')
                ->required()
                ->maxLength(150),
            TextInput::make('slug')
                ->label('Slug')
                ->disabled()
                ->dehydrated(false)
                ->helperText('Slug dibuat otomatis dari nama.'),
            Textarea::make('description')
                ->label('Deskripsi')
                ->columnSpanFull()
                ->rows(4),
            Toggle::make('is_active')
                ->label('Aktif')
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name')->searchable()->sortable(),
            TextColumn::make('slug')->searchable(),
            ToggleColumn::make('is_active'),
            TextColumn::make('created_at')->dateTime()->sortable(),
        ])->actions([
            EditAction::make(),
            DeleteAction::make(),
        ])->bulkActions([
            DeleteBulkAction::make(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNewsCategories::route('/'),
            'create' => Pages\CreateNewsCategory::route('/create'),
            'edit' => Pages\EditNewsCategory::route('/{record}/edit'),
        ];
    }
}