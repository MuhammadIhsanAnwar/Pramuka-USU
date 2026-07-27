<?php

namespace App\Filament\Admin\Resources\UserDataResource\Pages;

use App\Enums\RoleName;
use App\Filament\Admin\Resources\UserDataResource;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;

class ListUserData extends ListRecords
{
    protected static string $resource = UserDataResource::class;

    public function getTabs(): array
    {
        return [
            'biodata' => Tab::make('Biodata')->query(fn (Builder $query): Builder => $this->excludeAdminUsers($query)),
            'alamat' => Tab::make('Alamat')->query(fn (Builder $query): Builder => $this->excludeAdminUsers($query)),
            'riwayat-pendidikan' => Tab::make('Riwayat Pendidikan')->query(fn (Builder $query): Builder => $this->excludeAdminUsers($query)),
            'orang-tua' => Tab::make('Orang Tua')->query(fn (Builder $query): Builder => $this->excludeAdminUsers($query)),
            'data-pramuka' => Tab::make('Data Pramuka')->query(fn (Builder $query): Builder => $this->excludeAdminUsers($query)),
        ];
    }

    protected function excludeAdminUsers(Builder $query): Builder
    {
        return $query->whereDoesntHave('roles', fn ($query) => $query->where('name', RoleName::Admin->value));
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
