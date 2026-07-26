<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Laravel\Fortify\Contracts\ResetPasswordViewResponse;
use Laravel\Fortify\Fortify;

class PasswordResetController extends Controller
{
    /**
     * Display the password reset form only when its token is still valid.
     */
    public function create(Request $request, string $token): ResetPasswordViewResponse|RedirectResponse
    {
        $email = $request->query(Fortify::email());
        $broker = Password::broker(config('fortify.passwords'));
        $user = is_string($email) ? $broker->getUser([Fortify::email() => $email]) : null;

        if (! $user || ! $broker->tokenExists($user, $token)) {
            return redirect()
                ->route('password.request')
                ->withErrors([Fortify::email() => 'Tautan reset password tidak valid atau sudah kedaluwarsa.']);
        }

        return app(ResetPasswordViewResponse::class);
    }
}