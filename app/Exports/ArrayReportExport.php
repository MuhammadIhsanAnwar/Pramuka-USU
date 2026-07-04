<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ArrayReportExport implements FromCollection, WithHeadings
{
    /**
     * @param  array<int, array<int, string|int|null>>  $rows
     * @param  array<int, string>  $headings
     */
    public function __construct(
        private readonly array $rows,
        private readonly array $headings,
    ) {
    }

    public function collection(): Collection
    {
        return collect($this->rows)->map(static fn (array $row): array => array_values($row));
    }

    public function headings(): array
    {
        return $this->headings;
    }
}