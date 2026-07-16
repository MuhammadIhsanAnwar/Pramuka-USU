<?php

namespace App\Filament\Admin\Resources\AboutMemberResource\Pages;

use App\Filament\Admin\Resources\AboutMemberResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAboutMembers extends ListRecords
{
    protected static string $resource = AboutMemberResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()->label('Buat Anggota Tim')];
    }
}
