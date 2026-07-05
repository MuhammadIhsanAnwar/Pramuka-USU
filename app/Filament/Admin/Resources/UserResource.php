<?php

namespace App\Filament\Admin\Resources;

use App\Enums\RoleName;
use App\Filament\Admin\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
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
use UnitEnum;
use BackedEnum;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static string|UnitEnum|null $navigationGroup = 'Manajemen';

    protected static ?string $navigationLabel = 'Pengguna';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('name')
                    ->label('Nama')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Select::make('role_name')
                    ->label('Role')
                    ->options(collect(RoleName::cases())->mapWithKeys(fn (RoleName $role): array => [$role->value => $role->value])->all())
                    ->default(RoleName::User->value)
                    ->required(),
                Select::make('jenis_user')
                    ->label('Jenis User')
                    ->options([
                        'pembina' => 'Pembina',
                        'peserta_didik' => 'Peserta Didik',
                    ])
                    ->required(),
                TextInput::make('phone')
                    ->label('Telepon')
                    ->tel()
                    ->maxLength(30),
                DatePicker::make('birth_date')
                    ->label('Tanggal Lahir'),
                FileUpload::make('avatar_path')
                    ->label('Foto Profil')
                    ->image()
                    ->directory('avatars')
                    ->visibility('public')
                    ->maxSize(2048),
                Toggle::make('is_active')
                    ->label('Aktif'),
                Textarea::make('bio')
                    ->label('Bio')
                    ->columnSpanFull()
                    ->rows(4),
                Textarea::make('address')
                    ->label('Alamat')
                    ->columnSpanFull()
                    ->rows(4),
                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->revealable()
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->minLength(8)
                    ->dehydrated(fn (?string $state): bool => filled($state)),
            ]);
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
                    ->searchable(),
                TextColumn::make('roles.name')
                    ->label('Role')
                    ->badge(),
                TextColumn::make('jenis_user')
                    ->label('Jenis')
                    ->badge(),
                ToggleColumn::make('is_active')
                    ->label('Aktif'),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}