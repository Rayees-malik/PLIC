<?php

declare(strict_types=1);

namespace App\Listeners;

use Altek\Accountant\Events\Recording;
use App\Helpers\SignoffStateHelper;

class RecordingListener
{
    /**
     * Create the Recording event listener.
     */
    public function __construct()
    {
        // ...
    }

    /**
     * Handle the Recording event.
     *
     *
     * @return mixed
     */
    public function handle(Recording $event)
    {
        if (method_exists($event->model, 'stateField') && $event->model->{$event->model->stateField()} == SignoffStateHelper::IN_PROGRESS) {
            return false;
        }
    }
}
