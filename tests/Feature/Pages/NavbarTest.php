<?php

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Silber\Bouncer\BouncerFacade as Bouncer;

use function Pest\Laravel\get;

uses(DatabaseTransactions::class);

it('shows quality control under the products menu', function () {
    // Arrange
    $user = User::factory()->create();
    $user->assign('admin');

    // Act
    loginAsUser($user);

    get(route('home'))
        ->assertOk()
        ->assertSeeInOrder(['Products', 'Quality Control'])
        ->assertSee(route('qc.index'));
});

it('shows quality control menu option if user can view all qc records', function () {
    // Arrange
    $user = User::factory()->create();
    $user->allow('qc.view-all-qc-records');

    // Act
    loginAsUser($user);

    get(route('home'))
        ->assertOk()
        ->assertSee('Quality Control')
        ->assertSee(route('qc.index'));
});

it('shows quality control menu option if user has permssion', function () {
    // Arrange
    $user = User::factory()->create();
    Bouncer::allow($user)->to('qc.menu');

    // Act
    loginAsUser($user);

    get(route('home'))
        ->assertOk()
        ->assertSee('Quality Control')
        ->assertSee(route('qc.index'));
});
