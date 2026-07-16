<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UserDataResource\Pages;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use UnitEnum;

class UserDataResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-circle';

    protected static string|UnitEnum|null $navigationGroup = 'Manajemen Akun';

    protected static ?string $navigationLabel = 'Data Pengguna';

    public static function getPluralModelLabel(): string
    {
        return 'Data Pengguna';
    }

    public static function getSingularModelLabel(): string
    {
        return 'Data Pengguna';
    }

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('avatar_path')
                    ->label('Foto')
                    ->circular()
                    ->defaultImageUrl(fn (): string => asset('images/default-avatar.png')),
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('roles.name')
                    ->label('Role')
                    ->badge(),
                TextColumn::make('jenis_user')
                    ->label('Jenis Pengguna')
                    ->badge(),
                TextColumn::make('phone')
                    ->label('Telepon')
                    ->searchable(),
                TextColumn::make('birth_date')
                    ->label('Tanggal Lahir')
                    ->date(),
                TextColumn::make('address')
                    ->label('Alamat')
                    ->limit(50)
                    ->wrap(),
                TextColumn::make('bio')
                    ->label('Bio')
                    ->limit(80)
                    ->wrap(),
                ToggleColumn::make('is_active')
                    ->label('Aktif'),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUserData::route('/'),
        ];
    }
}
