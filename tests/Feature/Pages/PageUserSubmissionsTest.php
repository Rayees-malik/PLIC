<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

use function Pest\Laravel\get;

uses(DatabaseTransactions::class);

it('displays disclaimer about deleting draft and outdated signoffs', function () {
    tap(loginAsUser(), fn ($user) => $user->assign('admin'));

    get(route('user.submissions', ['filter' => 'rejected']))
        ->assertOk()
        ->assertSeeText('Please note that any drafts or outdated signoffs that have not been modified in the last 30 days are automatically deleted.');

    get(route('user.submissions', ['filter' => 'approved']))
        ->assertOk()
        ->assertSeeText('Please note that any drafts or outdated signoffs that have not been modified in the last 30 days are automatically deleted.');

    get(route('user.submissions', ['filter' => 'pending']))
        ->assertOk()
        ->assertSeeText('Please note that any drafts or outdated signoffs that have not been modified in the last 30 days are automatically deleted.');

        get(route('user.submissions', ['filter' => 'drafts']))
            ->assertOk()
            ->assertSeeText('Please note that any drafts or outdated signoffs that have not been modified in the last 30 days are automatically deleted.');

        get(route('user.submissions', ['filter' => 'outdated']))
            ->assertOk()
            ->assertSeeText('Please note that any drafts or outdated signoffs that have not been modified in the last 30 days are automatically deleted.');
});
