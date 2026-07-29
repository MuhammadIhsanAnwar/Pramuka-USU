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
        return 'bar';
    }

    protected function getData(): array
    {
        return Cache::remember('dashboard.admin.attendance-chart', now()->addMinutes(10), function (): array {
            $labels = [];
            $presentData = [];
            $lateData = [];

            for ($month = 1; $month <= 12; $month++) {
                $labels[] = date('M', mktime(0, 0, 0, $month, 1));
                $presentData[] = Attendance::query()->whereMonth('scanned_at', $month)->whereYear('scanned_at', now()->year)->where('status', 'hadir')->count();
                $lateData[] = Attendance::query()->whereMonth('scanned_at', $month)->whereYear('scanned_at', now()->year)->where('status', 'terlambat')->count();
            }

            return [
                'datasets' => [
                    [
                        'label' => 'Hadir',
                        'data' => $presentData,
                        'borderColor' => '#22c55e',
                        'backgroundColor' => 'rgba(34, 197, 94, 0.5)',
                        'fill' => true,
                    ],
                    [
                        'label' => 'Terlambat',
                        'data' => $lateData,
                        'borderColor' => '#f59e0b',
                        'backgroundColor' => 'rgba(245, 158, 11, 0.5)',
                        'fill' => true,
                    ],
                ],
                'labels' => $labels,
            ];
        });
    }
}