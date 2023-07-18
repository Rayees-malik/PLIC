<?php

use App\Actions\Database\DailyCleanupAction;
use App\Actions\Database\DeleteArchivedSignoffsAction;
use App\Actions\Database\DeleteDraftSignoffsAction;
use App\Actions\Database\RemoveDiscontinuedBrandCaseStackDealsAction;
use App\Actions\Database\RemoveDiscontinuedBrandPromosAction;
use App\Actions\Database\RemoveDiscontinuedProductPromosAction;
use YlsIdeas\FeatureFlags\Facades\Features;

beforeEach(fn () => $this->skip = Features::accessible('skip-daily-promo-cleanup-actions'));

it('runs action to remove promos for discontinued brands', function () {
    app(DailyCleanupAction::class)->execute();
})->shouldHaveCalledAction(RemoveDiscontinuedBrandPromosAction::class)
    ->skip(fn () => $this->skip);

it('runs action to remove case stack deals for discontinued brands', function () {
    app(DailyCleanupAction::class)->execute();
})->shouldHaveCalledAction(RemoveDiscontinuedBrandCaseStackDealsAction::class)
    ->skip(fn () => $this->skip);

it('runs action to remove promo line items for discontinued products', function () {
    app(DailyCleanupAction::class)->execute();
})->shouldHaveCalledAction(RemoveDiscontinuedProductPromosAction::class)
    ->skip(fn () => $this->skip);

it('runs action to delete outdated draft signoffs', function () {
    app(DailyCleanupAction::class)->execute();
})->shouldHaveCalledAction(DeleteDraftSignoffsAction::class);

it('runs action to delete outdated archived signoffs', function () {
    app(DailyCleanupAction::class)->execute();
})->shouldHaveCalledAction(DeleteArchivedSignoffsAction::class);
