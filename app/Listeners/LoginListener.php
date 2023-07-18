<?php

namespace App\Listeners;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class LoginListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        Log::channel('database')->info(json_encode([
            'event' => 'Login',
            'user' => $event->user->name,
            'time' => Carbon::now()->toDateTimeString(),
            'ip' => Request::ip(),
        ]));
    }
}
