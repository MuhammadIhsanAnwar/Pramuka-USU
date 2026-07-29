<?php

namespace App\Filament\Admin\Resources\EventAgendaResource\Pages;

use App\Filament\Admin\Resources\EventAgendaResource;
use App\Filament\Concerns\YearFilterable;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEventAgendas extends ListRecords
{
    use YearFilterable;

    protected function getTableHeader(): View | Htmlable | null
    {
        return $this->getYearFilterHeader();
    }

    protected function getTableQuery(): Builder | Relation
    {
        return $this->applyYearFilter(parent::getTableQuery(), 'starts_at');
    }

    protected static string $resource = EventAgendaResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()->label('Buat Agenda')];
    }
}