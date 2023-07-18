<?php

use App\Actions\Api\V1\RetrieveProducts;
use App\Jobs\Api\V1\CacheRetrievedProducts;
use Illuminate\Cache\Events\KeyWritten;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;

uses(DatabaseTransactions::class);

it('caches the data', function () {
    Event::fake();

    CacheRetrievedProducts::dispatch();

    Event::assertDispatched(KeyWritten::class, function ($event) {
        return Str::contains($event->key, RetrieveProducts::class);
    });
})->shouldHaveCalledAction(RetrieveProducts::class, '__invoke');
