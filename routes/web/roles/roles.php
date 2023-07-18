<?php

Route::prefix('roles')->middleware('can:users.roles.edit')->group(function () {
    // Create
    Route::get('create', [\App\Http\Controllers\RoleController::class, 'create'])->name('roles.create');
    Route::post('/', [\App\Http\Controllers\RoleController::class, 'store'])->name('roles.store');

    // Read
    Route::get('/', [\App\Http\Controllers\RoleController::class, 'index'])->name('roles.index');
    Route::get('{name}', [\App\Http\Controllers\RoleController::class, 'show'])->name('roles.show');

    // Update
    Route::get('{name}/edit', [\App\Http\Controllers\RoleController::class, 'edit'])->name('roles.edit');
    Route::patch('{name}', [\App\Http\Controllers\RoleController::class, 'update'])->name('roles.update');

    // Delete
    Route::delete('{name}', [\App\Http\Controllers\RoleController::class, 'destroy'])->name('roles.delete');
});
