<?php

Route::prefix('promos')->middleware('auth')->group(function () {
    // Disco Promos
    Route::prefix('disco')->group(function () {
        Route::get('/', [\App\Http\Controllers\DiscoPromoController::class, 'view'])
            ->middleware('can:promo.view.discos')
            ->name('discopromos.view');
        Route::get('edit', [\App\Http\Controllers\DiscoPromoController::class, 'edit'])
            ->middleware('can:promo.update.discos')
            ->name('discopromos.edit');
        Route::patch('/', [\App\Http\Controllers\DiscoPromoController::class, 'update'])
            ->middleware('can:promo.update.discos')
            ->name('discopromos.update');
    });

    // Promo Periods
    Route::prefix('periods')->group(function () {
        // Create
        Route::middleware('can:create,App\Models\PromoPeriod')->group(function () {
            Route::get('create', [\App\Http\Controllers\PromoController::class, 'promoPeriodsCreate'])->name('promos.periods.create');
            Route::post('/', [\App\Http\Controllers\PromoController::class, 'promoPeriodsStore'])->name('promos.periods.store');
            Route::post('generate', [\App\Http\Controllers\PromoController::class, 'promoPeriodsGenerate'])->name('promos.periods.generate');
        });

        // Read
        Route::middleware('can:view,App\Models\PromoPeriod')->group(function () {
            Route::get('/', [\App\Http\Controllers\PromoController::class, 'promoPeriodsIndex'])->name('promos.periods.index');
            Route::get('inactive', [\App\Http\Controllers\PromoController::class, 'promoPeriodsInactive'])->name('promos.periods.inactive');
        });
        Route::post('render', [\App\Http\Controllers\PromoController::class, 'promoPeriodsRenderSelect'])->name('promos.periods.render');

        // Update
        Route::middleware('can:edit,App\Models\PromoPeriod')->group(function () {
            Route::get('{id}/edit', [\App\Http\Controllers\PromoController::class, 'promoPeriodsEdit'])->name('promos.periods.edit');
            Route::get('{id}/toggle', [\App\Http\Controllers\PromoController::class, 'promoPeriodsToggleActive'])->name('promos.periods.toggle');
            Route::patch('{id}', [\App\Http\Controllers\PromoController::class, 'promoPeriodsUpdate'])->name('promos.periods.update');
        });

        // Delete
        // Route::delete('{id}', [\App\Http\Controllers\PromoController::class, 'destroy', 'middleware' => 'can:delete,App\Models\PromoPeriod'])->name('promos.periods.delete');
    });

    // Create
    Route::middleware('can:edit,App\Models\Promo')->group(function () {
        Route::get('create', [\App\Http\Controllers\PromoController::class, 'promosCreate'])->name('promos.create');
        Route::post('/', [\App\Http\Controllers\PromoController::class, 'promosStore'])->name('promos.store');
    });

    // Read
    Route::get('/', [\App\Http\Controllers\PromoController::class, 'promosIndex'])->name('promos.index');
    Route::get('{id}', [\App\Http\Controllers\PromoController::class, 'promosShow'])->name('promos.show');
    Route::middleware('can:edit,App\Models\Promo')->post('products/render', [\App\Http\Controllers\PromoController::class, 'promosRenderProductsTable'])->name('promos.products.render');

    // Update
    Route::middleware('can:edit,App\Models\Promo')->group(function () {
        Route::get('{id}/edit', [\App\Http\Controllers\PromoController::class, 'promosEdit', 'middleware' => 'can:edit,App\Models\Promo'])->name('promos.edit');
        Route::get('{id}/copy', [\App\Http\Controllers\PromoController::class, 'promosCopy', 'middleware' => 'can:edit,App\Models\Promo'])->name('promos.copy');
        Route::patch('{id}', [\App\Http\Controllers\PromoController::class, 'promosUpdate', 'middleware' => 'can:edit,App\Models\Promo'])->name('promos.update');
    });

    // Delete
    //Route::delete('{id}', [\App\Http\Controllers\PromoController::class, 'destroy', 'middleware' => 'can:delete,App\Models\Promo'])->name('promos.delete');
});
