<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminReportRouteTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_download_news_pdf_report(): void
    {
        Role::query()->create([
            'name' => 'Admin',
            'guard_name' => 'web',
        ]);

        $user = User::factory()->create();
        $user->assignRole('Admin');

        $this->actingAs($user)
            ->get(route('reports.news.pdf'))
            ->assertOk();
    }
}