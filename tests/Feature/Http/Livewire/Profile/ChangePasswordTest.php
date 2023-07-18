<?php

use App\Http\Livewire\Profile\ChangePassword;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;

uses(DatabaseTransactions::class);

test('the component can render', function () {
    $component = Livewire::test(ChangePassword::class);

    $component->assertStatus(200);
});

test('passwords must match', function () {
    Livewire::test(ChangePassword::class)
        ->set('password', 'password')
        ->set('password_confirmation', 'not-the-same-password')
        ->call('save')
        ->assertHasErrors(['password' => 'confirmed']);
});

test('password is required', function () {
    Livewire::test(ChangePassword::class)
        ->set('password', null)
        ->set('password_confirmation', 'password')
        ->call('save')
        ->assertHasErrors(['password' => 'required']);
});

test('password must have minimum length of 8', function () {
    Livewire::test(ChangePassword::class)
        ->set('password', str_repeat('a', 7))
        ->set('password_confirmation', str_repeat('a', 7))
        ->call('save')
        ->assertHasErrors(['password' => 'min']);
});

it('saves the password', function () {
    // Arrange
    $user = User::factory()->create([
        'password' => bcrypt('password'),
    ]);

    $user = loginAsUser($user);

    // Act
    Livewire::test(ChangePassword::class)
        ->set('password', str_repeat('a', 8))
        ->set('password_confirmation', str_repeat('a', 8))
        ->call('save')
        ->assertHasNoErrors();

    $user->refresh();

    // Assert
    expect(Hash::check('password', $user->password))->toBeFalse();
    expect(Hash::check(str_repeat('a', 8), $user->password))->toBeTrue();
});
