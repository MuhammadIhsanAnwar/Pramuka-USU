<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controller;

class MemberController extends Controller
{
    public function show(string $uuid): View
    {
        $member = User::where('uuid', $uuid)->firstOrFail();

        return view('public.member.profile', [
            'member' => $member,
        ]);
    }
}
