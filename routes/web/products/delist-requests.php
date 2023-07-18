<?php

Route::prefix('products/delist')->middleware('auth')->group(function () {
    // Create
    Route::get('create/{productId}', [\App\Http\Controllers\ProductDelistRequestController::class, 'create'])->name('productdelists.create');
    Route::post('create/{productId}', [\App\Http\Controllers\ProductDelistRequestController::class, 'store'])->name('productdelists.store');

    // Read
    Route::get('/', [\App\Http\Controllers\ProductDelistRequestController::class, 'index'])->name('productdelists.index');
    Route::get('{id}', [\App\Http\Controllers\ProductDelistRequestController::class, 'show'])->name('productdelists.show');

    // Update
    Route::get('{id}/edit', [\App\Http\Controllers\ProductDelistRequestController::class, 'edit'])->name('productdelists.edit');
    Route::patch('{id}', [\App\Http\Controllers\ProductDelistRequestController::class, 'update'])->name('productdelists.update');

    // Delete
});
