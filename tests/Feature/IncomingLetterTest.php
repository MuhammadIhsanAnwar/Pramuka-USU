<?php

namespace Tests\Feature;

use App\Models\IncomingLetter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IncomingLetterTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_store_incoming_letter_data(): void
    {
        $letter = IncomingLetter::create([
            'letter_date' => '2026-07-15',
            'sender' => 'Kwartir Daerah Sumatera Utara',
            'letter_number' => 'SM/001/2026',
            'classification' => 'Penting',
            'attachment' => '1 lembar',
            'subject' => 'Rapat Koordinasi',
            'file_path' => 'incoming-letters/sample.pdf',
        ]);

        $this->assertDatabaseHas('incoming_letters', [
            'id' => $letter->id,
            'letter_number' => 'SM/001/2026',
            'classification' => 'Penting',
        ]);
    }
}
