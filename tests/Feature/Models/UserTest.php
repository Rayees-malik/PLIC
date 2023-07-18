<?php

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpKernel\Exception\HttpException;

uses(DatabaseTransactions::class);

it('throws an unauthorized exception if user is not logged in when call scopeWithAccess', function () {
    User::withAccess()->get();
})->throws(HttpException::class);

it('can have a signature image', function () {
    // Arrange
    $user = User::factory()->create();

    $user->addMedia(UploadedFile::fake()->image('signature.png'))
        ->toMediaCollection('signature');

    // Assert
    expect($user->getSignature())->not()->toBeNull();
});

it('does not always have a signature image', function () {
    // Arrange
    $user = User::factory()->create();

    // Assert
    expect($user->getSignature())->toBeNull();
});
