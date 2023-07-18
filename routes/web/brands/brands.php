<?php

Route::prefix('brands')->middleware('auth')->group(function () {
    // Catalogue Categories
    Route::prefix('{brandId}/categories')->group(function () {
        Route::get('/', [\App\Http\Controllers\CatalogueCategoryController::class, 'index'])->name('brands.categories');
        Route::middleware('can:brand.edit.categories')->post('/', [\App\Http\Controllers\CatalogueCategoryController::class, 'update'])->name('brands.categories.update');

        Route::get('json', [\App\Http\Controllers\CatalogueCategoryController::class, 'jsonShow'])->name('brands.categories.json');
    });

    // Create
    Route::middleware('can:create,App\Models\Brand')->get('create', [\App\Http\Controllers\BrandController::class, 'create'])->name('brands.create');

    // Read
    Route::get('/', [\App\Http\Controllers\BrandController::class, 'index'])->name('brands.index');

    Route::get('search', [\App\Http\Controllers\BrandController::class, 'search'])->name('brands.search');
    Route::get('{id}/search/categories', [\App\Http\Controllers\BrandController::class, 'searchCategories'])->name('brands.search.categories');

    Route::get('{id}', [\App\Http\Controllers\BrandController::class, 'show'])->name('brands.show');

    // Update
    Route::middleware('can:create,App\Models\Brand')->post('submit', [\App\Http\Controllers\BrandController::class, 'submit'])->name('brands.submit');
    Route::middleware('can:edit,App\Models\Brand')->group(function () {
        Route::post('save', [\App\Http\Controllers\BrandController::class, 'backgroundSave'])->name('brands.save');
        Route::get('{id}/edit', [\App\Http\Controllers\BrandController::class, 'edit'])->name('brands.edit');
        Route::get('{id}/copy', [\App\Http\Controllers\BrandController::class, 'copy'])->name('brands.copy');
    });

    // Delete
    //Route::delete('{id}', ['as' => 'brands.delete', 'uses' => [\App\Http\Controllers\BrandController::class, 'destroy']]);
});

Route::get('brands/logo/{id}', [\App\Http\Controllers\BrandController::class, 'downloadLogo'])->name('brands.logo');
