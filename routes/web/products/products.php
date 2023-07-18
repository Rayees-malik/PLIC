<?php

Route::prefix('products')->middleware('auth')->group(function () {
    // Create
    Route::get('create', [\App\Http\Controllers\ProductController::class, 'create'])->name('products.create');

    // Read
    Route::get('/', [\App\Http\Controllers\ProductController::class, 'index'])->name('products.index');
    Route::middleware('can:product.view.submissions')->get('submissions/{type?}', [\App\Http\Controllers\ProductController::class, 'indexSubmissions'])->name('products.index.submissions');
    Route::get('{id}', [\App\Http\Controllers\ProductController::class, 'show'])->name('products.show');

    Route::post('search', [\App\Http\Controllers\ProductController::class, 'search'])->name('products.search');

    // Update
    Route::post('submit', [\App\Http\Controllers\ProductController::class, 'submit'])->name('products.submit');
    Route::post('save', [\App\Http\Controllers\ProductController::class, 'backgroundSave'])->name('products.save');
    Route::get('{id}/edit', [\App\Http\Controllers\ProductController::class, 'edit'])->name('products.edit');
    Route::get('{id}/copy', [\App\Http\Controllers\ProductController::class, 'copy'])->name('products.copy');
    Route::patch('{id}', [\App\Http\Controllers\ProductController::class, 'update'])->name('products.update');

    // Delete
    // Route::delete('{id}', ['as' => 'products.delete', 'uses' => [\App\Http\Controllers\ProductController::class, 'destroy'])->name('logout');
});

Route::get('products/image/{stock_id}', [\App\Http\Controllers\ProductController::class, 'downloadImage'])->name('products.image');
Route::get('products/labelflat/{stock_id}', [\App\Http\Controllers\ProductController::class, 'downloadLabelFlat'])->name('products.labelflat');
