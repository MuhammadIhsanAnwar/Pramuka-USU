<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
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

    public function test_user_can_reset_a_password_with_a_valid_token(): void
    {
        $user = User::factory()->create([
            'email' => 'member@example.test',
            'password' => Hash::make('old-password'),
        ]);

        $newPassword = 'New-password-123!';
        $token = Password::broker()->createToken($user);
        $resetLink = route('password.reset', ['token' => $token, 'email' => $user->email]);

        $this->get($resetLink)
            ->assertOk()
            ->assertSee('readonly');

        $this->post(route('password.update'), [
            'token' => $token,
            'email' => $user->email,
            'password' => $newPassword,
            'password_confirmation' => $newPassword,
        ])->assertRedirect('/');

        $this->assertTrue(Hash::check($newPassword, $user->fresh()->password));
        $this->get($resetLink)
            ->assertRedirect(route('password.request'))
            ->assertSessionHasErrors('email');
    }

    public function test_expired_reset_link_cannot_open_the_reset_form(): void
    {
        $user = User::factory()->create(['email' => 'expired@example.test']);
        $token = Password::broker()->createToken($user);

        DB::table(config('auth.passwords.users.table'))
            ->where('email', $user->email)
            ->update(['created_at' => now()->subMinutes(61)]);

        $this->get(route('password.reset', ['token' => $token, 'email' => $user->email]))
            ->assertRedirect(route('password.request'))
            ->assertSessionHasErrors('email');
    }
}
