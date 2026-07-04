<?php

namespace Tests\Feature;

use App\Models\NewsCategory;
use App\Models\NewsPost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicNewsIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_news_index_can_filter_by_category(): void
    {
        $featuredCategory = NewsCategory::query()->create([
            'name' => 'Kegiatan',
            'slug' => 'kegiatan',
            'description' => 'Liputan kegiatan',
            'is_active' => true,
        ]);

        $otherCategory = NewsCategory::query()->create([
            'name' => 'Pengumuman',
            'slug' => 'pengumuman',
            'description' => 'Informasi umum',
            'is_active' => true,
        ]);

        NewsPost::query()->create([
            'news_category_id' => $featuredCategory->id,
            'title' => 'Latihan Gabungan',
            'slug' => 'latihan-gabungan',
            'excerpt' => 'Latihan gabungan anggota berlangsung lancar.',
            'content' => '<p>Latihan gabungan anggota berlangsung lancar.</p>',
            'status' => 'publish',
            'published_at' => now(),
        ]);

        NewsPost::query()->create([
            'news_category_id' => $otherCategory->id,
            'title' => 'Jadwal Baru',
            'slug' => 'jadwal-baru',
            'excerpt' => 'Ada perubahan jadwal kegiatan.',
            'content' => '<p>Ada perubahan jadwal kegiatan.</p>',
            'status' => 'publish',
            'published_at' => now(),
        ]);

        $response = $this->get('/berita?kategori=kegiatan');

        $response->assertOk();
        $response->assertSee('Latihan Gabungan', false);
        $response->assertDontSee('Jadwal Baru', false);
        $response->assertSee('Semua', false);
    }
}