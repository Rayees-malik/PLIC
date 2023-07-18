<?php

Route::prefix('exports')->middleware('auth')->group(function () {
    // Listing Forms
    Route::group(['prefix' => 'listing'], function () {
        Route::get('/', [\App\Http\Controllers\ExportController::class, 'listingFormIndex'])->name('exports.listingforms.index');
        Route::post('/', [\App\Http\Controllers\ExportController::class, 'listingFormExport'])->name('exports.listingforms.export');
    });

    // Pricing Adjustment
    Route::get('pafupload/{id}', [\App\Http\Controllers\ExportController::class, 'pafUpload'])->name('exports.pafupload');
    Route::get('pafuploadwithmcb/{id}', [\App\Http\Controllers\ExportController::class, 'pafUploadWithMcb'])->name('exports.pafuploadwithmcb');

    // Marketing Agreements
    Route::get('mafjournal/{id}', [\App\Http\Controllers\ExportController::class, 'mafJournal'])->name('exports.mafjournal');
    Route::get('mafchargeback/{id}/{brandId}', [\App\Http\Controllers\ExportController::class, 'mafChargeBack'])->name('exports.mafchargeback');

    // Inventory Removals
    Route::get('printinvremoval/{id}', [\App\Http\Controllers\ExportController::class, 'printableInventoryRemoval'])->name('exports.printinvremoval');

    // Images
    Route::get('brandimages/{id}', [\App\Http\Controllers\ExportController::class, 'brandImages'])->name('exports.brandimages');

    // Basic Exports
    Route::get('/', [\App\Http\Controllers\ExportController::class, 'index'])->name('exports.index');
    Route::post('{name}', [\App\Http\Controllers\ExportController::class, 'export'])->name('exports.export');
});
