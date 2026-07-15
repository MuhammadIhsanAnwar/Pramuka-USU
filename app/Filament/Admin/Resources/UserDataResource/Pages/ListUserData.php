<?php

namespace App\Filament\Admin\Resources\UserDataResource\Pages;

use App\Filament\Admin\Resources\UserDataResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListUserData extends ListRecords
{
    protected static string $resource = UserDataResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
