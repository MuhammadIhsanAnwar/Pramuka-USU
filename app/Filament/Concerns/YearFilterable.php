<?php

namespace App\Filament\Concerns;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Livewire\Attributes\Url;

trait YearFilterable
{
    #[Url(as: 'year')]
    public int|string|null $year = null;

    protected static function getYearFilterQueryKey(): string
    {
        return 'year';
    }

    protected static function getYearRange(): array
    {
        $currentYear = now()->year;
        $minimumYear = min(2026, $currentYear);
        $maximumYear = max(2026, $currentYear);

        return range($minimumYear, $maximumYear);
    }

    protected function getSelectedYear(): int|string|null
    {
        if ($this->year === 'all') {
            return 'all';
        }

        if (is_numeric($this->year)) {
            return (int) $this->year;
        }

        return 'all';
    }

    protected function getEffectiveYear(): int|null
    {
        $selectedYear = $this->getSelectedYear();

        if (is_int($selectedYear)) {
            return $selectedYear;
        }

        return null;
    }

    protected function applyYearFilter(Builder|Relation $query, string $column): Builder|Relation
    {
        $effectiveYear = $this->getEffectiveYear();

        if ($effectiveYear === null) {
            return $query;
        }

        return $query->whereYear($column, $effectiveYear);
    }

    protected function getPageYearFilterData(): array
    {
        return [
            'years' => static::getYearRange(),
            'activeYear' => $this->getSelectedYear(),
            'queryKey' => static::getYearFilterQueryKey(),
            'baseUrl' => request()->url(),
            'query' => request()->except(static::getYearFilterQueryKey()),
        ];
    }

    protected function getYearFilterHeader(): View | Htmlable | null
    {
        return view('filament.components.year-filter-buttons', $this->getPageYearFilterData());
    }
}
