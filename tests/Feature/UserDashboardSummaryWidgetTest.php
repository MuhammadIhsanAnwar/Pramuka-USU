<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserDashboardSummaryWidgetTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_dashboard_renders_profile_summary_widget(): void
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.test',
            'is_active' => true,
        ]);

        $user->assignRole('User');

        $this->actingAs($user)
            ->get(route('filament.user.pages.dashboard'))
            ->assertOk()
            ->assertSee('Nomor Tanda Anggota')
            ->assertSee('Test User');
    }
}
