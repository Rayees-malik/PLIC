<?php

namespace App\Listeners;

use App\Events\SignoffUnsubmitted as SignoffUnsubmittedEvent;
use App\Notifications\Signoffs\SignoffUnsubmitted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class SignoffUnsubmittedListener implements ShouldQueue
{
    public function handle(SignoffUnsubmittedEvent $event)
    {
        Notification::send($event->users, new SignoffUnsubmitted($event->signoff));
    }
}
