<?php

namespace App\Filament\Admin\Pages;

use Filament\Auth\Pages\EditProfile as BaseEditProfile;
use Filament\Facades\Filament;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use LogicException;

class EditProfile extends BaseEditProfile
{
    protected static ?string $title = 'Edit Profil';

    public function getUser(): Authenticatable & Model
    {
        $user = Auth::guard(config('filament.auth.guard', 'web'))->user()
            ?? Filament::auth()->user();

        if ($user instanceof Model) {
            return $user;
        }

        if ($user instanceof Authenticatable) {
            $provider = Auth::createUserProvider(config('auth.guards.web.provider'));
            $resolvedUser = $provider?->retrieveById($user->getAuthIdentifier());

            if ($resolvedUser instanceof Model) {
                return $resolvedUser;
            }
        }

        throw new LogicException('The authenticated user object must be an Eloquent model to allow the profile page to update it.');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Profil')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                FileUpload::make('avatar_path')
                                    ->label('Foto Profil')
                                    ->image()
                                    ->disk('public')
                                    ->directory('avatars')
                                    ->visibility('public')
                                    ->columnSpanFull(),
                                TextInput::make('name')
                                    ->label('Nama')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('email')
                                    ->label('Email')
                                    ->email()
                                    ->required()
                                    ->disabled()
                                    ->maxLength(255),
                            ]),
                    ]),
            ]);
    }
}
