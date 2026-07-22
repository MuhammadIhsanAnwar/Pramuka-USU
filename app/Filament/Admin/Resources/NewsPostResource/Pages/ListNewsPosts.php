<?php

namespace App\Filament\Admin\Resources\NewsPostResource\Pages;

use App\Filament\Admin\Resources\NewsPostResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;

class ListNewsPosts extends ListRecords
{
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