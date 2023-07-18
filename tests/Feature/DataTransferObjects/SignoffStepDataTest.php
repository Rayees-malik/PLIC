<?php

use App\DataTransferObjects\SignoffStepData;
use App\Http\Requests\Signoffs\UpdateSignoffFormRequest;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\DataTransferObject\DataTransferObjectError;

uses(DatabaseTransactions::class);

it('can be created from update signoff form request', function () {
    loginAsUser();

    $dto = SignoffStepData::fromRequest(new UpdateSignoffFormRequest([
        'action' => 'approve',
        'signoff_comment' => 'foo',
        'signoff_step' => 1,
    ]));

    expect($dto)->toBeInstanceOf(SignoffStepData::class);
});

it('fails if not logged in', function () {
    $dto = SignoffStepData::fromRequest(new UpdateSignoffFormRequest([
        'action' => 'approve',
        'signoff_comment' => 'foo',
        'signoff_step' => 1,
    ]));
})->expectException(DataTransferObjectError::class);
