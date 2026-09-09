<?php

namespace App\Filament\Admin\Resources\AttendanceResource\Pages;

use App\Filament\Admin\Resources\AttendanceResource;
use App\Filament\Concerns\YearFilterable;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAttendances extends ListRecords
{
    use YearFilterable;

    protected function getTableHeader(): View | Htmlable | null
    {
        return $this->getYearFilterHeader();
    }

    protected function getTableQuery(): Builder | Relation
    {
        return $this->applyYearFilter(parent::getTableQuery(), 'scanned_at');
    }

    protected static string $resource = AttendanceResource::class;

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