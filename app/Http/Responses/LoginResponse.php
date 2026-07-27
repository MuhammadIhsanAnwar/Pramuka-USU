<?php

namespace App\Http\Responses;

use App\Enums\RoleName;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        if ($request->wantsJson()) {
            return new JsonResponse(['two_factor' => false]);
        }

        /** @var User|null $user */
        $user = Auth::user();

        if ($user instanceof User && $user->needsProfileCompletion()) {
            return redirect()->intended(url('/user'));
        }

        if ($user instanceof User && ! $user->is_active) {
            auth()->logout();

            return redirect()->route('login')
                ->withErrors(['email' => 'Akun Anda dinonaktifkan oleh admin. Silakan hubungi pihak yang berwenang.']);
        }

        if ($user instanceof User && $user->hasRole(RoleName::Admin->value)) {
            return redirect()->intended(url('/admin'));
        }

        return redirect()->intended(url('/user'));
    }
}
