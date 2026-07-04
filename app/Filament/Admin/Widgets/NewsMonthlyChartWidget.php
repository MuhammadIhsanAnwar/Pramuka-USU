<?php

namespace App\Filament\Admin\Widgets;

use App\Models\NewsPost;
use Illuminate\Support\Facades\Cache;
use Filament\Widgets\ChartWidget;

class NewsMonthlyChartWidget extends ChartWidget
{
    protected ?string $heading = 'Grafik Berita Bulanan';

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        return Cache::remember('dashboard.admin.news-chart', now()->addMinutes(10), function (): array {
            $labels = [];
            $data = [];

            for ($month = 1; $month <= 12; $month++) {
                $labels[] = date('M', mktime(0, 0, 0, $month, 1));
                $data[] = NewsPost::query()->whereMonth('published_at', $month)->whereYear('published_at', now()->year)->published()->count();
            }

            return [
                'datasets' => [
                    [
                        'label' => 'Berita Publish',
                        'data' => $data,
                        'backgroundColor' => '#C9A227',
                    ],
                ],
                'labels' => $labels,
            ];
        });
    }
}