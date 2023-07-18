<?php

// Vendors
Route::prefix('vendors')->middleware('auth')->group(function () {
    // Create
    Route::middleware('can:create,App\Models\Vendor')->get('create', [\App\Http\Controllers\VendorController::class, 'create'])->name('vendors.create');

    // Read
    Route::get('/', [\App\Http\Controllers\VendorController::class, 'index'])->name('vendors.index');
    Route::get('{id}', [\App\Http\Controllers\VendorController::class, 'show'])->name('vendors.show');

    // Update
    Route::middleware('can:create,App\Models\Vendor')->post('submit', [\App\Http\Controllers\VendorController::class, 'submit'])->name('vendors.submit');
    Route::middleware('can:edit,App\Models\Vendor')->group(function () {
        Route::post('save', [\App\Http\Controllers\VendorController::class, 'backgroundSave'])->name('vendors.save');
        Route::get('{id}/edit', [\App\Http\Controllers\VendorController::class, 'edit'])->name('vendors.edit');
    });

    // Delete
});
