<?php

use App\Actions\Signoffs\CheckIfSignoffCompleteAction;
use App\Actions\Signoffs\SendSignoffNotificationAction;
use App\DataTransferObjects\SignoffStepData;
use App\Models\Product;
use App\Models\Signoff;
use App\Models\SignoffConfig;
use App\Models\SignoffResponse;
use App\Notifications\Signoffs\ProductListingNotification;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(DatabaseTransactions::class);

it('checks if the signoff is already complete', function () {
    $user = User::factory()->create();

    $product = Product::factory()->catalogueActive()->create();

    $signoff = Signoff::factory()
        ->for($user)
        ->for($user)
        ->for($product, 'initial')
        ->for($product->duplicate(), 'proposed')
        ->for(SignoffConfig::where('model', Product::class)->first())
        ->approveUpToStep(5)
        ->newSubmission()
        ->create();

    $signoffStepData = new SignoffStepData([
        'action' => 'reject',
        'comment' => 'It was rejected',
        'step' => 5,
        'user' => $user,
        'notification' => new ProductListingNotification($signoff, $user, 'lorem ipsum'),
    ]);

    $signoffResponse = SignoffResponse::factory()->create([
        'signoff_id' => $signoff->id,
        'user_id' => $user->id,
        'approved' => true,
        'step' => 5,
    ]);

    $signoffStepData = $signoffStepData->forSignoff($signoff);
    $signoffStepData = $signoffStepData->withResponse($signoffResponse);

    $action = resolve(SendSignoffNotificationAction::class);
    $action->execute($signoffStepData);
})->shouldHaveCalledAction(CheckIfSignoffCompleteAction::class)->skip('Review if still needed after refactoring');
