<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminReportRouteTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_download_all_report_routes(): void
    {
        Role::query()->create([
            'name' => 'Admin',
            'guard_name' => 'web',
        ]);

        $user = User::factory()->create();
        $user->assignRole('Admin');

        $reportRoutes = [
            route('reports.news.pdf'),
            route('reports.news.excel'),
            route('reports.agenda.pdf'),
            route('reports.agenda.excel'),
            route('reports.attendance.pdf'),
            route('reports.attendance.excel'),
            route('reports.user.pdf'),
            route('reports.user.excel'),
        ];

        foreach ($reportRoutes as $reportRoute) {
            $this->actingAs($user)
                ->get($reportRoute)
                ->assertOk();
        }
    }
}