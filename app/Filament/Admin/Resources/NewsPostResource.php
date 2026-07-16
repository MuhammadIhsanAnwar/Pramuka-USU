<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\NewsPostResource\Pages;
use App\Models\NewsPost;
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

class NewsPostResource extends Resource
{
    protected static ?string $model = NewsPost::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-newspaper';

    protected static string|UnitEnum|null $navigationGroup = 'Manajemen Konten';

    protected static ?string $navigationLabel = 'Berita';

    public static function getPluralModelLabel(): string
    {
        return 'Berita';
    }

    public static function getSingularModelLabel(): string
    {
        return 'Berita';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Select::make('news_category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                TextInput::make('title')
                    ->label('Judul')
                    ->required()
                    ->maxLength(255),
                TextInput::make('slug')
                    ->label('Slug')
                    ->disabled()
                    ->dehydrated(false)
                    ->helperText('Slug dibuat otomatis dari judul.'),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'publish' => 'Publish',
                    ])
                    ->required()
                    ->default('draft'),
                FileUpload::make('thumbnail_path')
                    ->label('Thumbnail')
                    ->image()
                    ->directory('news/thumbnails')
                    ->visibility('public')
                    ->maxSize(4096),
                DateTimePicker::make('published_at')
                    ->label('Tanggal Publish'),
                TextInput::make('excerpt')
                    ->label('Ringkasan')
                    ->columnSpanFull()
                    ->maxLength(500),
                RichEditor::make('content')
                    ->label('Isi Berita')
                    ->columnSpanFull()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('thumbnail_path')
                    ->label('Thumbnail')
                    ->square(),
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(40),
                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge(),
                BadgeColumn::make('status')
                    ->colors([
                        'gray' => 'draft',
                        'success' => 'publish',
                    ]),
                TextColumn::make('author.name')
                    ->label('Penulis')
                    ->toggleable(),
                TextColumn::make('published_at')
                    ->label('Publish')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('viewer_count')
                    ->label('Viewer')
                    ->sortable(),
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
            'index' => Pages\ListNewsPosts::route('/'),
            'create' => Pages\CreateNewsPost::route('/create'),
            'edit' => Pages\EditNewsPost::route('/{record}/edit'),
        ];
    }
}