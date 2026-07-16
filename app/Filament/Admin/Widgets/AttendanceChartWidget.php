<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Attendance;
use Illuminate\Support\Facades\Cache;
use Filament\Widgets\ChartWidget;

class AttendanceChartWidget extends ChartWidget
{
    protected ?string $heading = 'Grafik Kehadiran';

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        return Cache::remember('dashboard.admin.attendance-chart', now()->addMinutes(10), function (): array {
            $labels = [];
            $data = [];

            for ($month = 1; $month <= 12; $month++) {
                $labels[] = date('M', mktime(0, 0, 0, $month, 1));
                $data[] = Attendance::query()->whereMonth('scanned_at', $month)->whereYear('scanned_at', now()->year)->count();
            }

            return [
                'datasets' => [
                    [
                        'label' => 'Presensi',
                        'data' => $data,
                        'borderColor' => '#3E271A',
                        'backgroundColor' => 'rgba(62, 39, 26, 0.2)',
                        'fill' => true,
                    ],
                ],
                'labels' => $labels,
            ];
        });
    }
}