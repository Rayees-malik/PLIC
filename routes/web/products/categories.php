<?php

Route::prefix('categories')->middleware('auth')->group(function () {
    // Create

    // Read
    Route::post('{id}/subcategories', [\App\Http\Controllers\ProductCategoryController::class, 'subcategories'])->name('categories.subcategories');

    // Update

    // Delete
});
