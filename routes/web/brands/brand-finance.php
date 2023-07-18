<?php

Route::prefix('payments-deductions')->middleware('auth')->group(function () {
    // Create

    // Update
    Route::post('force-upload', [\App\Http\Controllers\BrandFinanceController::class, 'forceUpload'])->name('brand-finance.force-upload');

    // Delete
    Route::middleware('can:finance.delete-media')->delete('media/{id}/delete', [\App\Http\Controllers\BrandFinanceController::class, 'destroyMedia'])->name('brand-finance.destroy-media');

    // Read
    Route::post('table-data', [\App\Http\Controllers\BrandFinanceController::class, 'tableData'])->name('brand-finance.tableData');
    Route::any('{brand_id?}/{tab?}', [\App\Http\Controllers\BrandFinanceController::class, 'index'])->name('brand-finance.index');
});
