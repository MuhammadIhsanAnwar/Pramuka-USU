<?php

namespace App\Filament\Admin\Resources;

use App\Enums\RoleName;
use App\Filament\Admin\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use UnitEnum;
use BackedEnum;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static string|UnitEnum|null $navigationGroup = 'Manajemen Akun';

    protected static ?string $navigationLabel = 'Pengguna';

    public static function getPluralModelLabel(): string
    {
        return 'Pengguna';
    }

    public static function getSingularModelLabel(): string
    {
        return 'Pengguna';
    }

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
                    ->required()
                    ->reactive(),
                Select::make('jenis_user')
                    ->label('Jenis Pengguna')
                    ->options([
                        'pembina' => 'Pembina',
                        'peserta_didik' => 'Peserta Didik',
                    ])
                    ->required(fn (callable $get): bool => $get('role_name') !== RoleName::Admin->value)
                    ->hidden(fn (callable $get): bool => $get('role_name') === RoleName::Admin->value),
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
                    ->label('Jenis Pengguna')
                    ->badge(),
                ToggleColumn::make('is_active')
                    ->label('Aktif')
                    ->hidden(fn (?User $record): bool => Filament::auth()->id() !== null && Filament::auth()->id() === $record?->id),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make()
                    ->visible(fn (User $record): bool => Filament::auth()->id() !== $record->id)
                    ->disabled(fn (User $record): bool => Filament::auth()->id() === $record->id),
            ])
            ->bulkActions([]);
    }

    public static function canDelete(Model $record): bool
    {
        return Filament::auth()->id() !== $record->id && parent::canDelete($record);
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