<?php

Route::prefix('brokers')->middleware('can:lookups.edit')->group(function () {
    // Create
    Route::get('create', [\App\Http\Controllers\BrokerController::class, 'create'])->name('brokers.create');
    Route::post('/', [\App\Http\Controllers\BrokerController::class, 'store'])->name('brokers.store');

    // Read
    Route::get('/', [\App\Http\Controllers\BrokerController::class, 'index'])->name('brokers.index');
    Route::get('{id}', [\App\Http\Controllers\BrokerController::class, 'show'])->name('brokers.show');

    // Update
    Route::get('{id}/edit', [\App\Http\Controllers\BrokerController::class, 'edit'])->name('brokers.edit');
    Route::patch('{id}', [\App\Http\Controllers\BrokerController::class, 'update'])->name('brokers.update');

    // Delete
    Route::delete('{id}', [\App\Http\Controllers\BrokerController::class, 'destroy'])->name('brokers.delete');
});
