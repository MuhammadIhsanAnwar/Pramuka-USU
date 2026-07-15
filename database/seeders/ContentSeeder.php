<?php

namespace Database\Seeders;

use App\Models\AboutGroup;
use App\Models\NewsCategory;
use App\Models\SiteSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Kegiatan', 'Pengumuman', 'Prestasi'] as $categoryName) {
            NewsCategory::query()->firstOrCreate(
                ['name' => $categoryName],
                [
                    'slug' => Str::slug($categoryName),
                    'is_active' => true,
                ],
            );
        }

        $settings = [
            ['setting_key' => 'site_name', 'setting_group' => 'general', 'label' => 'Nama Situs', 'setting_type' => 'text', 'setting_value' => 'Pramuka USU', 'is_public' => true],
            ['setting_key' => 'hero_title', 'setting_group' => 'home', 'label' => 'Judul Hero', 'setting_type' => 'text', 'setting_value' => 'Gerakan Pramuka Universitas Sumatera Utara', 'is_public' => true],
            ['setting_key' => 'hero_subtitle', 'setting_group' => 'home', 'label' => 'Subjudul Hero', 'setting_type' => 'textarea', 'setting_value' => 'Wadah pembinaan karakter, kepemimpinan, dan pengabdian di lingkungan Universitas Sumatera Utara.', 'is_public' => true],
            ['setting_key' => 'contact_email', 'setting_group' => 'contact', 'label' => 'Email Kontak', 'setting_type' => 'text', 'setting_value' => 'pramuka@usu.ac.id', 'is_public' => true],
            ['setting_key' => 'contact_phone', 'setting_group' => 'contact', 'label' => 'Telepon Kontak', 'setting_type' => 'text', 'setting_value' => '+62 61 12345678', 'is_public' => true],
        ];

        foreach ($settings as $setting) {
            SiteSetting::query()->firstOrCreate(
                ['setting_key' => $setting['setting_key']],
                $setting,
            );
        }

        foreach ([
            ['name' => 'Majelis Pembimbing Gugus Depan', 'slug' => 'majelis-pembimbing-gugus-depan', 'description' => 'Pembimbing dan penasihat yang mengarahkan kegiatan Gugus Depan.', 'order' => 0, 'is_active' => true],
            ['name' => 'Gugus Depan', 'slug' => 'gugus-depan', 'description' => 'Unit Gugus Depan yang menyelenggarakan pembinaan dan kegiatan Pramuka USU.', 'order' => 1, 'is_active' => true],
            ['name' => 'Racana', 'slug' => 'racana', 'description' => 'Racana tingkat kampus untuk anggota Pramuka USU.', 'order' => 2, 'is_active' => true],
            ['name' => 'Ambalan', 'slug' => 'ambalan', 'description' => 'Ambalan sebagai dasar pelaksanaan aktivitas dasar Pramuka dan pengembangan jiwa kepimpinan.', 'order' => 3, 'is_active' => true],
        ] as $groupData) {
            AboutGroup::query()->firstOrCreate(
                ['slug' => $groupData['slug']],
                $groupData,
            );
        }
    }
}