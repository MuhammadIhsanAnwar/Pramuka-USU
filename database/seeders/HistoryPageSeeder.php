<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\HistoryPage;

class HistoryPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        HistoryPage::query()->updateOrCreate([
            'title' => 'Sejarah',
        ], [
            'lead' => 'Gerakan Pramuka di USU tumbuh sebagai bagian dari penguatan karakter mahasiswa dan kader kepemimpinan.',
            'content' => '<p>Unit ini berperan aktif dalam kegiatan kampus, pembinaan internal, dan jejaring antar gugus depan.</p><p>Perjalanan organisasi dibangun di atas semangat Dasa Dharma dan Tri Satya.</p>',
            'is_active' => true,
        ]);
    }
}
