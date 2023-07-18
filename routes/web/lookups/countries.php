<?php

Route::prefix('countries')->middleware('can:lookups.edit')->group(function () {
    // Create
    Route::get('create', [\App\Http\Controllers\CountryController::class, 'create'])->name('countries.create');
    Route::post('/', [\App\Http\Controllers\CountryController::class, 'store'])->name('countries.store');

    // Read
    Route::get('/', [\App\Http\Controllers\CountryController::class, 'index'])->name('countries.index');
    Route::get('{id}', [\App\Http\Controllers\CountryController::class, 'show'])->name('countries.show');

    // Update
    Route::get('{id}/edit', [\App\Http\Controllers\CountryController::class, 'edit'])->name('countries.edit');
    Route::patch('{id}', [\App\Http\Controllers\CountryController::class, 'update'])->name('countries.update');

    // Delete
    Route::delete('{id}', [\App\Http\Controllers\CountryController::class, 'destroy'])->name('countries.delete');
});
