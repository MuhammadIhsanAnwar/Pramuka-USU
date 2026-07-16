<?php

namespace App\Filament\Admin\Resources\EventAgendaResource\Pages;

use App\Filament\Admin\Resources\EventAgendaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEventAgendas extends ListRecords
{
    protected static string $resource = EventAgendaResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()->label('Buat Agenda')];
    }
}