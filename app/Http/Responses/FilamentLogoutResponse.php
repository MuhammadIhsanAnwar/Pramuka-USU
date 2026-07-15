<?php

namespace App\Http\Responses;

use Filament\Auth\Http\Responses\Contracts\LogoutResponse as FilamentLogoutResponseContract;
use Illuminate\Http\RedirectResponse;

class FilamentLogoutResponse implements FilamentLogoutResponseContract
{
    public function toResponse($request): RedirectResponse
    {
        return redirect('/');
    }
}
