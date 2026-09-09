<?php

namespace App\Filament\Admin\Resources\PresensiSessionResource\Pages;

use App\Filament\Admin\Resources\PresensiSessionResource;
use App\Filament\Concerns\YearFilterable;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPresensiSessions extends ListRecords
{
    use YearFilterable;

    protected static string $resource = PresensiSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Buat Presensi'),
            Action::make('exportPdf')
                ->label('PDF Presensi')
                ->icon('heroicon-o-document-text')
                ->url(fn (): string => route('reports.attendance.pdf'))
                ->openUrlInNewTab(),
            Action::make('exportExcel')
                ->label('Excel Presensi')
                ->icon('heroicon-o-arrow-down-tray')
                ->url(fn (): string => route('reports.attendance.excel'))
                ->openUrlInNewTab(),
        ];
    }
}
