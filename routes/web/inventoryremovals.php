<?php

Route::prefix('removals')->middleware('auth')->group(function () {
    // Create
    Route::get('create', [\App\Http\Controllers\InventoryRemovalController::class, 'create'])->name('inventoryremovals.create');
    Route::post('/', [\App\Http\Controllers\InventoryRemovalController::class, 'store'])->name('inventoryremovals.store');

    // Read
    Route::get('/', [\App\Http\Controllers\InventoryRemovalController::class, 'index'])->name('inventoryremovals.index');
    Route::get('{id}', [\App\Http\Controllers\InventoryRemovalController::class, 'show'])->name('inventoryremovals.show');

    Route::post('productsearch', [\App\Http\Controllers\InventoryRemovalController::class, 'productSearch'])->name('inventoryremovals.products.search');

    // Update
    Route::get('{id}/edit', [\App\Http\Controllers\InventoryRemovalController::class, 'edit'])->name('inventoryremovals.edit');
    Route::patch('{id}', [\App\Http\Controllers\InventoryRemovalController::class, 'update'])->name('inventoryremovals.update');

    // Delete
    //Route::delete('{id}', [\App\Http\Controllers\InventoryRemovalController::class, 'destroy'])->name('inventoryremovals.delete');
});
