<?php

Route::prefix('mafs')->middleware('auth')->group(function () {
    // Create
    Route::get('create', [\App\Http\Controllers\MarketingAgreementController::class, 'create'])->name('marketingagreements.create');
    Route::post('/', [\App\Http\Controllers\MarketingAgreementController::class, 'store'])->name('marketingagreements.store');

    // Read
    Route::get('/', [\App\Http\Controllers\MarketingAgreementController::class, 'index'])->name('marketingagreements.index');
    Route::get('{id}', [\App\Http\Controllers\MarketingAgreementController::class, 'show'])->name('marketingagreements.show');

    // Update
    Route::get('{id}/edit', [\App\Http\Controllers\MarketingAgreementController::class, 'edit'])->name('marketingagreements.edit');
    Route::patch('{id}', [\App\Http\Controllers\MarketingAgreementController::class, 'update'])->name('marketingagreements.update');

    // Delete
    //Route::delete('{id}', [\App\Http\Controllers\MarketingAgreementController::class, 'destroy'])->name('marketingagreements.delete');
});
