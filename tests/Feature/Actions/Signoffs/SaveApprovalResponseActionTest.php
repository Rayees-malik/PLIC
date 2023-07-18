<?php

use App\Actions\Signoffs\SaveApprovalResponseAction;
use App\DataTransferObjects\SignoffStepData;
use App\Models\Product;
use App\Models\Signoff;
use App\Models\SignoffConfig;
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(DatabaseTransactions::class);

it('creates an approval response', function () {
    $user = $this->signIn()->assign('admin');

    $product = Product::factory()->catalogueActive()->create();

    $signoff = Signoff::factory()
        ->submitted()
        ->for($user)
        ->for($product, 'initial')
        ->for($product->duplicate(), 'proposed')
        ->for(SignoffConfig::where('model', Product::class)->first())
        ->create();

    $signoffStepData = new SignoffStepData([
        'action' => 'approve',
        'comment' => 'It was approved',
        'step' => $signoff->step,
        'user' => $user,
    ]);

    $action = app(SaveApprovalResponseAction::class);
    $action->execute($signoffStepData->forSignoff($signoff));

    $lastResponse = $signoff->responses()->latest('id')->first();
    expect($lastResponse->approved)->toBeTruthy();
    expect($lastResponse->comment)->toBe('It was approved');
})->skip('Need to refactor to single response action class tests');
