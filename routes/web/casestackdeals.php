<?php

Route::prefix('casestackdeals')->middleware('auth')->group(function () {
    // Create

    // Read
    Route::get('{brandId?}', [\App\Http\Controllers\CaseStackDealController::class, 'index'])->name('casestackdeals.index');

    // Update
    Route::patch('/', [\App\Http\Controllers\CaseStackDealController::class, 'update'])->name('casestackdeals.update');

    // Delete
});
