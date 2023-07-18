<?php

use App\Console\Commands\CreateApiUserCommand;
use App\Models\ApiUser;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\PersonalAccessToken;

use function Pest\Laravel\artisan;

uses(DatabaseTransactions::class);

it('creates a user and api token', function () {
    artisan(CreateApiUserCommand::class, [
        'name' => 'John Doe',
    ])->assertExitCode(0);

    $user = ApiUser::first();

    expect(ApiUser::count())->toBe(1);
    expect(PersonalAccessToken::count())->toBe(1);
    expect($user->name)->toBe('John Doe');
    expect($user->tokens()->count())->toBe(1);
    expect($user->tokens()->first()->tokenable->id)->toBe($user->id);
});
