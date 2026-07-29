<?php

namespace App\Filament\Admin\Resources\NewsPostResource\Pages;

use App\Filament\Admin\Resources\NewsPostResource;
use App\Filament\Concerns\YearFilterable;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;

class ListNewsPosts extends ListRecords
{
    use YearFilterable;

    protected function getTableHeader(): View | Htmlable | null
    {
        return $this->getYearFilterHeader();
    }

    protected function getTableQuery(): Builder | Relation
    {
        return $this->applyYearFilter(parent::getTableQuery(), 'published_at');
    }

    protected static string $resource = NewsPostResource::class;

    public function getTabs(): array
    {
        return [
            'draft' => Tab::make('Berita Kiriman User')
                ->icon('heroicon-o-inbox-arrow-down')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'draft')),
            'publish' => Tab::make('Berita Dipublish')
                ->icon('heroicon-o-check-circle')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'publish')),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()->label('Buat Berita')];
    }
}