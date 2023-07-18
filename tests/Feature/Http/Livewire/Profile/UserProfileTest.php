<?php

use App\Http\Livewire\Profile\UserProfile;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

uses(DatabaseTransactions::class);

it('can render the component', function () {
    $this->signIn();

    $component = Livewire::test(UserProfile::class);
    $component->assertStatus(200);
});

it('can update user profile', function () {
    $user = loginAsUser();

    Livewire::test(UserProfile::class)
        ->set('name', 'Test User')
        ->set('email', 'tester@example.com')
        ->call('save');

    $user->refresh();

    expect($user->name)->toBe('Test User');
    expect($user->email)->toBe('tester@example.com');
});

it('can save an image of signature', function () {
    Storage::fake('tmp-for-tests');

    $user = loginAsUser();

    $file = UploadedFile::fake()->image('signature.png');

    Livewire::test(UserProfile::class)
        ->set('newSignature', $file)
        ->call('save');

    expect($user->refresh()->getSignature())->not()->toBeNull();
});

it('displays the existing signature', function () {
    $user = loginAsUser();

    Livewire::test(UserProfile::class)
        ->set('newSignature', null)
        ->call('save')
        ->assertOk();
});

it('a signature is not required', function () {
    Storage::fake('tmp-for-tests');
    $user = loginAsUser();

    $file = UploadedFile::fake()->image('signature.png');

    $user->addMedia($file)
        ->toMediaCollection('signature');

    Livewire::test(UserProfile::class)
        ->assertSeeHtml($user->getSignature()->getUrl());
});
