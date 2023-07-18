<?php

use App\Actions\Signoffs\DetermineSignoffNextStepAction;
use App\Actions\Signoffs\GoToNextSignoffStepAction;
use App\Actions\Signoffs\ProcessSignoffAction;
use App\Actions\Signoffs\ResolveSignoffFlowStateAction;
use App\Actions\Signoffs\ResolveSignoffNotificationAction;
use App\Actions\Signoffs\SaveSignoffResponseAction;
use App\Actions\Signoffs\SendSignoffNotificationAction;
use App\DataTransferObjects\SignoffStepData;
use App\Models\Product;
use App\Models\Signoff;
use App\Models\SignoffConfig;
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(DatabaseTransactions::class);

it('execuates all required actions', function () {
    $user = $this->signIn('admin');
    $product = Product::factory()->catalogueActive()->create();

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

    $signoffStepData = new SignoffStepData([
        'action' => 'approve',
        'step' => 1,
        'user' => $user,
    ]);

    $signoffStepData = $signoffStepData->forSignoff($signoff);

    $action = resolve(ProcessSignoffAction::class);

    $action->execute($signoffStepData);
})
    ->shouldHaveCalledAction(SaveSignoffResponseAction::class)
    ->shouldHaveCalledAction(DetermineSignoffNextStepAction::class)
    ->shouldHaveCalledAction(ResolveSignoffFlowStateAction::class)
    ->shouldHaveCalledAction(ResolveSignoffNotificationAction::class)
    ->shouldHaveCalledAction(SendSignoffNotificationAction::class)
    ->shouldHaveCalledAction(GoToNextSignoffStepAction::class);
