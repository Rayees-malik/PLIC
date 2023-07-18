<?php

Route::prefix('retailers')->middleware('auth')->group(function () {
    // Promos
    Route::prefix('{ownerId}/promos')->group(function () {
        Route::post('periods/render', [\App\Http\Controllers\RetailerController::class, 'promoPeriodsRenderSelect'])->name('retailers.promos.periods.render');

        Route::middleware('can:edit,App\Models\Retailer')->prefix('periods')->group(function () {
            // Create
            Route::middleware('can:create,App\Models\PromoPeriod')->group(function () {
                Route::get('create', [\App\Http\Controllers\RetailerController::class, 'promoPeriodsCreate'])->name('retailers.promos.periods.create');
                Route::post('/', [\App\Http\Controllers\RetailerController::class, 'promoPeriodsStore'])->name('retailers.promos.periods.store');
                Route::post('generate', [\App\Http\Controllers\RetailerController::class, 'promoPeriodsGenerate'])->name('retailers.promos.periods.generate');
            });

            // Read
            Route::middleware('can:view,App\Models\PromoPeriod')->group(function () {
                Route::get('/', [\App\Http\Controllers\RetailerController::class, 'promoPeriodsIndex'])->name('retailers.promos.periods.index');
                Route::get('inactive', [\App\Http\Controllers\RetailerController::class, 'promoPeriodsInactive'])->name('retailers.promos.periods.inactive');
            });

            // Update
            Route::middleware('can:edit,App\Models\PromoPeriod')->group(function () {
                Route::get('{id}/edit', [\App\Http\Controllers\RetailerController::class, 'promoPeriodsEdit'])->name('retailers.promos.periods.edit');
                Route::get('{id}/toggle', [\App\Http\Controllers\RetailerController::class, 'promoPeriodsToggleActive'])->name('retailers.promos.periods.toggle');
                Route::patch('{id}', [\App\Http\Controllers\RetailerController::class, 'promoPeriodsUpdate'])->name('retailers.promos.periods.update');
            });

            // Delete
            // Route::delete('{id}', [\App\Http\Controllers\RetailerController::class, 'destroy', 'middleware' => 'can:delete,App\Models\PromoPeriod'])->name('retailers.promos.periods.delete');
        });

        // Create
        Route::middleware('can:edit,App\Models\Promo')->group(function () {
            Route::get('create', [\App\Http\Controllers\RetailerController::class, 'promosCreate'])->name('retailers.promos.create');
            Route::post('/', [\App\Http\Controllers\RetailerController::class, 'promosStore'])->name('retailers.promos.store');
        });

        // Read
        Route::get('/', [\App\Http\Controllers\RetailerController::class, 'promosIndex'])->name('retailers.promos.index');
        Route::get('{id}', [\App\Http\Controllers\RetailerController::class, 'promosShow'])->name('retailers.promos.show');
        Route::middleware('can:edit,App\Models\Promo')->post('products/render', [\App\Http\Controllers\RetailerController::class, 'promosRenderProductsTable'])->name('retailers.promos.products.render');

        // Update
        Route::middleware('can:edit,App\Models\Promo')->group(function () {
            Route::get('{id}/edit', [\App\Http\Controllers\RetailerController::class, 'promosEdit'])->name('retailers.promos.edit');
            Route::patch('{id}', [\App\Http\Controllers\RetailerController::class, 'promosUpdate'])->name('retailers.promos.update');
        });

        // Delete
        //Route::middleware('can:delete,App\Models\Promo')->delete('{id}', [\App\Http\Controllers\RetailerController::class, 'destroy'])->name('retailers.promos.delete');
    });

    // Create
    Route::middleware('can:edit,App\Models\Retailer')->get('create', [\App\Http\Controllers\RetailerController::class, 'create'])->name('retailers.create');
    Route::middleware('can:edit,App\Models\Retailer')->post('/', [\App\Http\Controllers\RetailerController::class, 'store'])->name('retailers.store');

    // Read
    Route::get('promos', [\App\Http\Controllers\RetailerController::class, 'promosOwnedIndex'])->name('retailers.promos.general.index');
    Route::middleware('can:view,App\Models\Retailer')->get('{id}', [\App\Http\Controllers\RetailerController::class, 'show'])->name('retailers.show');
    Route::middleware('can:view,App\Models\Retailer')->get('/', [\App\Http\Controllers\RetailerController::class, 'index'])->name('retailers.index');

    // Update
    Route::middleware('can:edit,App\Models\Retailer')->get('{id}/edit', [\App\Http\Controllers\RetailerController::class, 'edit'])->name('retailers.edit');
    Route::middleware('can:edit,App\Models\Retailer')->patch('{id}', [\App\Http\Controllers\RetailerController::class, 'update'])->name('retailers.update');
    Route::middleware('can:edit,App\Models\Retailer')->post('submit', [\App\Http\Controllers\RetailerController::class, 'submit'])->name('retailers.submit');
    Route::middleware('can:edit,App\Models\Retailer')->post('save', [\App\Http\Controllers\RetailerController::class, 'backgroundSave'])->name('retailers.save');

    // Imports / Exports
    Route::middleware('can:edit,App\Models\Retailer')->get('{id}/imports', [\App\Http\Controllers\RetailerController::class, 'imports'])->name('retailers.imports');
    Route::middleware('can:edit,App\Models\Retailer')->post('{id}/imports/listings', [\App\Http\Controllers\RetailerController::class, 'importListings'])->name('retailers.imports.listings');

    Route::middleware('can:edit,App\Models\Retailer')->get('{id}/exports', [\App\Http\Controllers\RetailerController::class, 'exports'])->name('retailers.exports');
    Route::middleware('can:edit,App\Models\Retailer')->post('{id}/exports/wfpromo', [\App\Http\Controllers\RetailerController::class, 'wfPromoExport'])->name('retailers.exports.wfpromo');
    Route::middleware('can:edit,App\Models\Retailer')->post('{id}/exports/wfcanadapromo', [\App\Http\Controllers\RetailerController::class, 'wfCanadaPromoExport'])->name('retailers.exports.wfcanadapromo');
    Route::middleware('can:edit,App\Models\Retailer')->post('{id}/exports/nfpromo', [\App\Http\Controllers\RetailerController::class, 'nfPromoExport'])->name('retailers.exports.nfpromo');
    Route::middleware('can:edit,App\Models\Retailer')->post('{id}/exports/defaultpromo', [\App\Http\Controllers\RetailerController::class, 'defaultPromoExport'])->name('retailers.exports.defaultpromo');

    // Delete
    //Route::delete('{id}', [\App\Http\Controllers\RetailerController::class, 'destroy'])->name('retailers.delete');
});
