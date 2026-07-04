<?php

namespace Tests\Feature;

use App\Models\EventAgenda;
use App\Models\Gallery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicAgendaAndGalleryDetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_agenda_detail_page_renders(): void
    {
        $agenda = EventAgenda::query()->create([
            'name' => 'Kemah Pramuka',
            'slug' => 'kemah-pramuka',
            'location' => 'Lapangan',
            'description' => 'Kemah gabungan anggota.',
            'starts_at' => now()->addDays(5),
            'status' => 'published',
        ]);

        $response = $this->get(route('agenda.show', $agenda));

        $response->assertOk();
        $response->assertSee('Kemah Pramuka', false);
        $response->assertSee('Lapangan', false);
    }

    public function test_gallery_detail_page_renders(): void
    {
        $gallery = Gallery::query()->create([
            'title' => 'Dokumentasi Kebersamaan',
            'album' => 'Kegiatan',
            'image_path' => 'photos/sample.jpg',
            'description' => 'Foto kegiatan bersama.',
        ]);

        $response = $this->get(route('gallery.show', $gallery));

        $response->assertOk();
        $response->assertSee('Dokumentasi Kebersamaan', false);
        $response->assertSee('Kegiatan', false);
    }
}
