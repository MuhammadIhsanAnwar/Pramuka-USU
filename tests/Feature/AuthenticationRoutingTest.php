<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AuthenticationRoutingTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_routes_redirect_guests_to_their_panel_login_pages(): void
    {
        $this->get('/admin')
            ->assertRedirect(route('filament.admin.auth.login'));

        $this->get('/user')
            ->assertRedirect(route('filament.user.auth.login'));

        $this->get(route('filament.admin.auth.login'))
            ->assertOk();

        $this->get(route('filament.user.auth.login'))
            ->assertOk();

        $this->get('/dashboard')
            ->assertRedirect(route('filament.user.pages.dashboard'));
    }

    public function test_forgot_password_sends_a_reset_link_to_a_registered_user(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'email' => 'member@example.test',
        ]);

        $this->from(route('password.request'))
            ->post(route('password.email'), ['email' => $user->email])
            ->assertRedirect(route('password.request'))
            ->assertSessionHas('status');

        Notification::assertSentTo($user, ResetPassword::class);
    }
}
