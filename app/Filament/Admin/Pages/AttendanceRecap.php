<?php

namespace App\Filament\Admin\Pages;

use App\Models\EventAgenda;
use Filament\Actions\Action;
use Filament\Pages\Page;

class AttendanceRecap extends Page
{
    protected static ?string $navigationLabel = 'Rekapitulasi Presensi';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static string|\UnitEnum|null $navigationGroup = 'Manajemen Kegiatan';
    protected string $view = 'filament.admin.rekapitulasi-presensi';

    public array $agendas = [];

    protected function getHeaderActions(): array
    {
        return [
            Action::make('exportPdf')
                ->label('PDF Rekap Presensi')
                ->icon('heroicon-o.document-text')
                ->url(fn (): string => route('reports.attendance_recap.pdf'))
                ->openUrlInNewTab(),
            Action::make('exportExcel')
                ->label('Excel Rekap Presensi')
                ->icon('heroicon-o.arrow-down-tray')
                ->url(fn (): string => route('reports.attendance_recap.excel'))
                ->openUrlInNewTab(),
        ];
    }

    public function mount(): void
    {
        $this->agendas = EventAgenda::published()
            ->withCount([
                'attendances as total_attendances',
                'attendances as hadir_count' => function ($q) { $q->where('status', 'hadir'); },
                'attendances as terlambat_count' => function ($q) { $q->where('status', 'terlambat'); },
                'attendances as izin_count' => function ($q) { $q->where('status', 'izin'); },
                'attendances as tidak_count' => function ($q) { $q->where('status', 'tidak'); },
            ])
            ->orderBy('starts_at', 'desc')
            ->get()
            ->toArray();
    }
}
