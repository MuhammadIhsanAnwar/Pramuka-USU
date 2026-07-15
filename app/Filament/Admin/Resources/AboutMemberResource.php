<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\AboutMemberResource\Pages;
use App\Models\AboutMember;
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
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use BackedEnum;
use UnitEnum;

class AboutMemberResource extends Resource
{
    protected static ?string $model = AboutMember::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected static string|UnitEnum|null $navigationGroup = 'Manajemen Tim';

    protected static ?string $navigationLabel = 'Anggota Tim';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Select::make('about_group_id')
                    ->label('Grup')
                    ->relationship('group', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                TextInput::make('name')
                    ->label('Nama')
                    ->required()
                    ->maxLength(255),
                TextInput::make('position')
                    ->label('Jabatan')
                    ->maxLength(255),
                TextInput::make('order')
                    ->label('Urutan')
                    ->numeric()
                    ->default(0),
                FileUpload::make('photo_path')
                    ->label('Foto')
                    ->image()
                    ->imageAspectRatio('3:4')
                    ->automaticallyCropImagesToAspectRatio()
                    ->imageEditor()
                    ->automaticallyOpenImageEditorForAspectRatio()
                    ->automaticallyResizeImagesMode('cover')
                    ->automaticallyResizeImagesToWidth('1200')
                    ->automaticallyResizeImagesToHeight('1600')
                    ->automaticallyUpscaleImagesWhenResizing(false)
                    ->imagePreviewHeight('280')
                    ->helperText('Unggah foto maksimal 2MB dengan rasio 3:4. Jika file terlalu besar, kompres gambar sebelum mengunggah.')
                    ->directory('about-members')
                    ->visibility('public')
                    ->maxSize(2048),
                Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('photo_path')
                    ->label('Foto')
                    ->rounded()
                    ->square(),
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('position')
                    ->label('Jabatan')
                    ->sortable(),
                TextColumn::make('group.name')
                    ->label('Grup')
                    ->sortable(),
                TextColumn::make('order')
                    ->label('Urutan')
                    ->sortable(),
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
            'index' => Pages\ListAboutMembers::route('/'),
            'create' => Pages\CreateAboutMember::route('/create'),
            'edit' => Pages\EditAboutMember::route('/{record}/edit'),
        ];
    }
}
