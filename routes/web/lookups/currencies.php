<?php

Route::prefix('currencies')->middleware('can:lookups.edit')->group(function () {
    // Create
    Route::get('create', [\App\Http\Controllers\CurrencyController::class, 'create'])->name('currencies.create');
    Route::post('/', [\App\Http\Controllers\CurrencyController::class, 'store'])->name('currencies.store');

    // Read
    Route::get('/', [\App\Http\Controllers\CurrencyController::class, 'index'])->name('currencies.index');
    Route::get('{id}', [\App\Http\Controllers\CurrencyController::class, 'show'])->name('currencies.show');

    // Update
    Route::get('{id}/edit', [\App\Http\Controllers\CurrencyController::class, 'edit'])->name('currencies.edit');
    Route::patch('{id}', [\App\Http\Controllers\CurrencyController::class, 'update'])->name('currencies.update');

    // Delete
    Route::delete('{id}', [\App\Http\Controllers\CurrencyController::class, 'destroy'])->name('currencies.delete');
});
