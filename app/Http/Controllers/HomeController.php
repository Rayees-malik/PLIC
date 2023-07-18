<?php

namespace App\Http\Controllers;

use App\Models\UpcomingChange;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $upcomingChanges = UpcomingChange::query()
            ->where('expires_at', '>', now())
            ->where('scheduled_at', '<=', now())
            ->orderBy('change_date', 'asc')
            ->get();

        return view('home', [
            'upcomingChanges' => $upcomingChanges,
        ]);
    }
}
