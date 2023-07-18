<?php

Route::prefix('pafs')->middleware('auth')->group(function () {
    // Create
    Route::get('create', [\App\Http\Controllers\PricingAdjustmentController::class, 'create'])->name('pricingadjustments.create');
    Route::post('/', [\App\Http\Controllers\PricingAdjustmentController::class, 'store'])->name('pricingadjustments.store');

    // Read
    Route::get('/', [\App\Http\Controllers\PricingAdjustmentController::class, 'index'])->name('pricingadjustments.index');
    Route::get('{id}', [\App\Http\Controllers\PricingAdjustmentController::class, 'show'])->name('pricingadjustments.show');

    Route::post('productsearch', [\App\Http\Controllers\PricingAdjustmentController::class, 'productSearch'])->name('pricingadjustments.products.search');

    // Update
    Route::get('{id}/edit', [\App\Http\Controllers\PricingAdjustmentController::class, 'edit'])->name('pricingadjustments.edit');
    Route::patch('{id}', [\App\Http\Controllers\PricingAdjustmentController::class, 'update'])->name('pricingadjustments.update');

    // Delete
    //Route::delete('{id}', [\App\Http\Controllers\PricingAdjustmentController::class, 'destroy'])->name('pricingadjustments.delete');
});
