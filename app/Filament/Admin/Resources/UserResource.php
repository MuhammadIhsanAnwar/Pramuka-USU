<?php

namespace App\Filament\Admin\Resources;

use App\Enums\RoleName;
use App\Filament\Admin\Resources\UserResource\Pages;
use App\Models\User;
use App\Services\MemberQrCodeService;
use Filament\Actions\Action as TableAction;
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
                    ->reactive()
                    ->disabled(fn (callable $get): bool => $get('role_name') === RoleName::Admin->value),
                Select::make('jenis_user')
                    ->label('Jenis Pengguna')
                    ->options([
                        'pembina' => 'Pembina',
                        'peserta_didik' => 'Peserta Didik',
                        'purna' => 'Purna',
                        'tamu' => 'Tamu',
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
                ImageColumn::make('qr_code_url')
                    ->label('QR Code')
                    ->height(80)
                    ->width(80)
                    ->toggleable(false)
                    ->url(fn (?string $state, $record): ?string => $record?->qr_code_url),
                ImageColumn::make('avatar_url')
                    ->label('Foto')
                    ->height(80)
                    ->width(60)
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
                    ->badge()
                    ->formatStateUsing(fn ($state): ?string => $state ? ucwords(str_replace('_', ' ', is_string($state) ? $state : $state->value)) : null),
                ToggleColumn::make('is_active')
                    ->label('Aktif')
                    ->disabled(fn (?User $record): bool => $record?->hasRole(RoleName::Admin->value)),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                TableAction::make('generateQr')
                    ->label('Generate QR')
                    ->color('secondary')
                    ->action(static function (User $record): void {
                        app(MemberQrCodeService::class)->generateFor($record);
                    })
                    ->successNotificationTitle('QR Code dibuat')
                    ->visible(fn (User $record): bool => blank($record->qr_code_path)),
                TableAction::make('regenerateQr')
                    ->label('Regenerate QR')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Regenerate QR Code')
                    ->action(static function (User $record): void {
                        app(MemberQrCodeService::class)->regenerateFor($record);
                    })
                    ->successNotificationTitle('QR Code berhasil digenerate ulang'),
                EditAction::make()
                    ->visible(fn (User $record): bool => Filament::auth()->id() !== $record->id),
                DeleteAction::make()
                    ->visible(fn (User $record): bool => Filament::auth()->id() !== $record->id),
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