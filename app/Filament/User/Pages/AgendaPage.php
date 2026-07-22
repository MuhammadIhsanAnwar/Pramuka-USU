<?php

namespace App\Filament\User\Pages;

use App\Models\EventAgenda;
use BackedEnum;
use Filament\Pages\Page;
use UnitEnum;

class AgendaPage extends Page
{
    protected static ?string $title = 'Agenda';
    protected static ?string $slug = 'agenda';
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationLabel = 'Agenda';
    protected static string | UnitEnum | null $navigationGroup = 'User';
    protected string $view = 'filament.user.pages.agenda';

    public function getViewData(): array
    {
        return [
            'agendas' => EventAgenda::query()
                ->published()
                ->orderBy('starts_at')
                ->get(),
        ];
    }
}
