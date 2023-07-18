<?php

Route::prefix('uom')->middleware('can:lookups.edit')->group(function () {
    // Create
    Route::get('create', [\App\Http\Controllers\UnitOfMeasureController::class, 'create'])->name('uom.create');
    Route::post('/', [\App\Http\Controllers\UnitOfMeasureController::class, 'store'])->name('uom.store');

    // Read
    Route::get('/', [\App\Http\Controllers\UnitOfMeasureController::class, 'index'])->name('uom.index');
    Route::get('{id}', [\App\Http\Controllers\UnitOfMeasureController::class, 'show'])->name('uom.show');

    // Update
    Route::get('{id}/edit', [\App\Http\Controllers\UnitOfMeasureController::class, 'edit'])->name('uom.edit');
    Route::patch('{id}', [\App\Http\Controllers\UnitOfMeasureController::class, 'update'])->name('uom.update');

    // Delete
    Route::delete('{id}', [\App\Http\Controllers\UnitOfMeasureController::class, 'destroy'])->name('uom.delete');
});
