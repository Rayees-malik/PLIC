<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

use function Pest\Laravel\get;

uses(DatabaseTransactions::class);

it('gives back successful response for dashboard page', function () {
    // Act & Assert
    $this->signIn();

    get(route('home'))
        ->assertOk();
});
