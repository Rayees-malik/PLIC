<?php

use App\Actions\Database\DailyCleanupAction;

it('calls the daily cleanup action', function () {
    $this->artisan('cleanup:daily');
})->shouldHaveCalledAction(DailyCleanupAction::class);
