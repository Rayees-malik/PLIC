<?php

namespace App\Http\Controllers;

class ProfileController extends Controller
{
    public function edit()
    {
        $unsubscriptions = auth()->user()->unsubscriptions;

        return view('users.profile.edit', compact('unsubscriptions'));
    }
}
