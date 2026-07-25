<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class RouteController extends Controller
{
    /**
     * Redirect /admin/surat-masuk to Filament CRUD index.
     */
    public function redirectAdminSuratMasuk(): RedirectResponse
    {
        return redirect()->route('filament.admin.resources.incoming-letters.index');
    }

    /**
     * Redirect /dashboard to the user panel at /user.
     */
    public function redirectDashboard(): RedirectResponse
    {
        return redirect()->to(url('/user'));
    }
}

