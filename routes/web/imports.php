<?php

Route::prefix('imports')->middleware('can:imports.viewmenu')->group(function () {
    // Marketing Agreements
    Route::middleware('can:imports.glaccounts')->post('glaccounts', [\App\Http\Controllers\ImportController::class, 'customerGLAccounts'])->name('imports.glaccounts');

    Route::middleware('can:imports.pricing-update')->post('pricing-update', [\App\Http\Controllers\ImportController::class, 'pricingUpdate'])->name('imports.pricing-update');

    // Basic Imports
    Route::get('/', [\App\Http\Controllers\ImportController::class, 'index'])->name('imports.index');
});
