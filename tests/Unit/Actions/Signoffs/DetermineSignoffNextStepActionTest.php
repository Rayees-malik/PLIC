<?php

use App\Actions\Signoffs\DetermineSignoffNextStepAction;
use App\DataTransferObjects\SignoffStepData;
use App\Models\Product;
use App\Models\Signoff;
use App\Models\SignoffConfig;
use App\Models\SignoffResponse;
use App\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(DatabaseTransactions::class);

it('returns the correct next step when approving and only one signoff is required', function () {
    $user = User::factory()->create();

    $product = Product::factory()->catalogueActive()->create([
        'category_id' => 1,
    ]);

    $signoff = Signoff::factory()
        ->submitted()
        ->for($user)
        ->for($product, 'initial')
        ->for($product->duplicate(), 'proposed')
        ->for(SignoffConfig::where('model', Product::class)->first())
        ->create([
            'new_submission' => true,
            'step' => 1,
        ]);

    $signoffResponse = SignoffResponse::factory()->create([
        'signoff_id' => $signoff->id,
        'user_id' => $user->id,
        'approved' => true,
        'step' => 1,
    ]);

    $signoffStepData = new SignoffStepData([
        'action' => 'approve',
        'comment' => 'It was approved',
        'step' => 1,
        'user' => $user,
    ]);

    $signoffStepData = $signoffStepData->forSignoff($signoff);
    $signoffStepData = $signoffStepData->withResponse($signoffResponse);

    $action = resolve(DetermineSignoffNextStepAction::class);
    $data = $action->execute($signoffStepData);

    expect($data->nextStep)->toBe(3);
});

it('returns the correct next step when approving and two or more signoffs are required', function () {
    $user = User::factory()->create();

    $product = Product::factory()->catalogueActive()->create();

    $signoff = Signoff::factory()
        ->for($user)
        ->for($product, 'initial')
        ->for($product->duplicate(), 'proposed')
        ->for(SignoffConfig::where('model', Product::class)->first())
        ->create([
            'step' => 4,
        ]);

    $signoffResponse = SignoffResponse::factory()->state(new Sequence(
        ['step' => 1],
        ['step' => 3],
        ['step' => 4],
    ))->create([
        'signoff_id' => $signoff->id,
        'user_id' => $user->id,
        'approved' => true,
    ]);

    $signoffStepData = new SignoffStepData([
        'action' => 'approve',
        'comment' => 'It was approved',
        'step' => 4,
        'user' => $user,
    ]);

    $signoffStepData = $signoffStepData->forSignoff($signoff);
    $signoffStepData = $signoffStepData->withResponse($signoffResponse);

    $action = resolve(DetermineSignoffNextStepAction::class);
    $data = $action->execute($signoffStepData);

    expect($data->nextStep)->toBe(4);
});

it('returns the correct next step when rejecting and qc is not required', function () {
    $user = User::factory()->create();

    $product = Product::factory()->catalogueActive()->create([
        'category_id' => 1,
    ]);

    $signoff = Signoff::factory()
        ->for($user)
        ->for($product, 'initial')
        ->for($product->duplicate(), 'proposed')
        ->for(SignoffConfig::where('model', Product::class)->first())
        ->submitted()
        ->newSubmission()
        ->create([
            'step' => 3,
        ]);

    $signoffResponse = SignoffResponse::factory()->create([
        'signoff_id' => $signoff->id,
        'user_id' => $user->id,
        'approved' => true,
        'step' => 1,
    ]);

    $signoffResponse = SignoffResponse::factory()->create([
        'signoff_id' => $signoff->id,
        'user_id' => $user->id,
        'approved' => false,
        'step' => 3,
    ]);

    $signoffStepData = new SignoffStepData([
        'action' => 'reject',
        'comment' => 'It was rejected',
        'step' => 3,
        'user' => $user,
    ]);

    $signoffStepData = $signoffStepData->forSignoff($signoff);
    $signoffStepData = $signoffStepData->withResponse($signoffResponse);

    $action = resolve(DetermineSignoffNextStepAction::class);
    $data = $action->execute($signoffStepData);

    expect($data->nextStep)->toBe(1);
});
