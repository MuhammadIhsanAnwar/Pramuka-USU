<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\HistoryPageResource\Pages;
use App\Models\HistoryPage;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use BackedEnum;
use UnitEnum;

class HistoryPageResource extends Resource
{
    protected static ?string $model = HistoryPage::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-book-open';

    protected static string|UnitEnum|null $navigationGroup = 'Manajemen Konten';

    protected static ?string $navigationLabel = 'Sejarah';

    public static function getPluralModelLabel(): string
    {
        return 'Sejarah';
    }

    public static function getSingularModelLabel(): string
    {
        return 'Sejarah';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('title')
                    ->label('Judul')
                    ->required()
                    ->maxLength(255),
                Textarea::make('lead')
                    ->label('Lead')
                    ->rows(3)
                    ->columnSpanFull(),
                FileUpload::make('photo_paths')
                    ->label('Foto Sejarah')
                    ->image()
                    ->multiple()
                    ->directory('history-pages')
                    ->visibility('public')
                    ->maxSize(4096)
                    ->columnSpanFull(),
                RichEditor::make('content')
                    ->label('Konten')
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('lead')
                    ->label('Lead')
                    ->limit(80),
                ToggleColumn::make('is_active')
                    ->label('Aktif'),
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
            'index' => Pages\ListHistoryPages::route('/'),
            'create' => Pages\CreateHistoryPage::route('/create'),
            'edit' => Pages\EditHistoryPage::route('/{record}/edit'),
        ];
    }
}
