<?php

use App\Models\UpcomingChange;
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(DatabaseTransactions::class);

test('description is not required', function () {
    $upcomingChange = UpcomingChange::create([
        'title' => 'Test Change',
        'change_date' => '2022-06-01',
        'expires_at' => '2022-06-30',
        'scheduled_at' => '2022-05-01',
        'description' => null,
    ]);

    expect(UpcomingChange::count())->toBe(1);

    expect($upcomingChange->refresh()->description)->toBeNull();
});

test('title cannot be longer than 255 characters', function () {
    $upcomingChange = UpcomingChange::create([
        'title' => 'Test Change',
        'description' => str_repeat('a', 256),
        'change_date' => '2022-06-01',
        'expires_at' => '2022-06-30',
        'scheduled_at' => '2022-05-01',
    ]);
})->throws('Data too long for column \'description\'');

test('title cannot be null', function () {
    $upcomingChange = UpcomingChange::create([
        'title' => null,
        'change_date' => '2022-06-01',
        'expires_at' => '2022-06-30',
        'scheduled_at' => '2022-05-01',
    ]);
})->throws('Column \'title\' cannot be null');

test('change date cannot be null', function () {
    UpcomingChange::create([
        'title' => 'Test Change',
        'change_date' => null,
        'expires_at' => '2022-06-30',
        'scheduled_at' => '2022-05-01',
    ]);
})->throws('Column \'change_date\' cannot be null');

test('scheduled at cannot be null', function () {
    UpcomingChange::create([
        'title' => 'Test Change',
        'change_date' => '2022-06-01',
        'expires_at' => '2022-06-30',
        'scheduled_at' => null,
    ]);
})->throws('Column \'scheduled_at\' cannot be null');

test('expires at cannot be null', function () {
    UpcomingChange::create([
        'title' => 'Test Change',
        'change_date' => '2022-06-01',
        'expires_at' => null,
        'scheduled_at' => '2022-05-01',
    ]);
})->throws('Column \'expires_at\' cannot be null');
