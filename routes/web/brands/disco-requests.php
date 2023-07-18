<?php

Route::prefix('brands/disco')->middleware('auth')->group(function () {
    // Create
    Route::middleware('can:brand.disco.request')->get('create/{brandId}', [\App\Http\Controllers\BrandDiscoRequestController::class, 'create'])->name('branddiscos.create');
    Route::middleware('can:brand.disco.request')->post('create/{brandId}', [\App\Http\Controllers\BrandDiscoRequestController::class, 'store'])->name('branddiscos.store');

    // Read
    Route::middleware('can:brand.disco.request')->get('/', [\App\Http\Controllers\BrandDiscoRequestController::class, 'index'])->name('branddiscos.index');
    Route::middleware('can:brand.disco.request')->get('{id}', [\App\Http\Controllers\BrandDiscoRequestController::class, 'show'])->name('branddiscos.show');

    // Update
    Route::middleware('can:brand.disco.request')->get('{id}/edit', [\App\Http\Controllers\BrandDiscoRequestController::class, 'edit'])->name('branddiscos.edit');
    Route::middleware('can:brand.disco.request')->patch('{id}', [\App\Http\Controllers\BrandDiscoRequestController::class, 'update'])->name('branddiscos.update');

    // Delete
});
