<?php

Route::prefix('abilities')->middleware('can:users.roles.edit')->group(function () {
    // Create
    Route::get('create', [\App\Http\Controllers\AbilityController::class, 'create'])->name('abilities.create');
    Route::post('/', [\App\Http\Controllers\AbilityController::class, 'store'])->name('abilities.store');

    // Read
    Route::get('/', [\App\Http\Controllers\AbilityController::class, 'index'])->name('abilities.index');
    Route::get('{name}/view', [\App\Http\Controllers\AbilityController::class, 'show'])->name('abilities.show');

    // Update
    Route::get('{name}/edit', [\App\Http\Controllers\AbilityController::class, 'edit'])->name('abilities.edit');
    Route::patch('{name}', [\App\Http\Controllers\AbilityController::class, 'update'])->name('abilities.update');

    // Delete
    Route::delete('{name}', [\App\Http\Controllers\AbilityController::class, 'destroy'])->name('abilities.delete');
});
