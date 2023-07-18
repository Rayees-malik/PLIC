<?php

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(DatabaseTransactions::class);

test('non admins cannot impersonate', function () {
    $userToImpersonate = User::factory()->create();
    $userToImpersonate->assign('costing-specialist');

    $nonAdmin = User::factory()->create();
    $nonAdmin->assign('costing-specialist');
    $this->actingAs($nonAdmin);

    $this->assertFalse($userToImpersonate->canImpersonate());
});

test('admins can impersonate', function () {
    $user = User::factory()->create();
    $user->assign('admin');
    $this->actingAs($user);

    $this->assertTrue($user->canImpersonate());
});

test('user cannot impersonate self', function () {
    $user = User::factory()->create();
    $user->assign('admin');
    $this->actingAs($user);

    $this->assertFalse($user->canBeImpersonated());
});

test('cannot impersonate if already impersonating', function () {
    $loggedInUser = User::factory()->create();
    $loggedInUser->assign('admin');

    $impersonating = User::factory()->create();
    $impersonating->assign('admin');

    $notAbleToImpersonate = User::factory()->create();
    $notAbleToImpersonate->assign('admin');

    $this->actingAs($loggedInUser);

    $this->get(route('impersonate', $impersonating->id));

    $this->assertFalse($notAbleToImpersonate->canBeImpersonated());
});

test('impersonate links not visible if already impersonating', function () {
    $loggedInUser = User::factory()->create();
    $loggedInUser->assign('admin');

    $impersonating = User::factory()->create();
    $impersonating->assign('admin');

    $otherUser = User::factory()->create();
    $otherUser->assign('admin');

    $this->actingAs($loggedInUser);

    $this->get(route('impersonate', $impersonating->id));

    $response = $this->getJson('/users', ['HTTP_X-Requested-With' => 'XMLHttpRequest']);

    $response->assertDontSee(trim(json_encode(route('impersonate', $loggedInUser->id)), '"'), false);
    $response->assertDontSee(trim(json_encode(route('impersonate', $impersonating->id)), '"'), false);
    $response->assertDontSee(trim(json_encode(route('impersonate', $otherUser->id)), '"'), false);
});

test('impersonate link not visible for currently logged in user', function () {
    $otherUser = User::factory()->create();
    $otherUser->assign('costing-specialist');

    $user = User::factory()->create();
    $user->assign('admin');
    $this->actingAs($user);

    $response = $this->getJson('/users', ['HTTP_X-Requested-With' => 'XMLHttpRequest']);

    $response->assertDontSee(trim(json_encode(route('impersonate', $user->id)), '"'), false);
    $response->assertSee(trim(json_encode(route('impersonate', $otherUser->id)), '"'), false);
});

test('impersonate link not visible if user not allowed to impersonate other users', function () {
    $userToImpersonate = User::factory()->create();
    $userToImpersonate->assign('costing-specialist');

    $user = User::factory()->create();
    $user->assign('admin');
    $user->forbid('impersonate-users');
    $this->actingAs($user);

    $response = $this->getJson('/users', ['HTTP_X-Requested-With' => 'XMLHttpRequest']);

    $response->assertDontSee(trim(json_encode(route('impersonate', $user->id)), '"'), false);
    $response->assertDontSee(trim(json_encode(route('impersonate', $userToImpersonate->id)), '"'), false);
});

test('user cannot impersonate if ability forbidden', function () {
    $user = User::factory()->create();
    $user->assign('admin');
    $user->forbid('impersonate-users');

    expect($user->canImpersonate())->toBeFalse();
});
