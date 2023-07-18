<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::group([], __DIR__ . '/web/auth.php');

// User routes
Route::group([], __DIR__ . '/web/users.php');

// Retailers
Route::group([], __DIR__ . '/web/retailers.php');

// MAFs
Route::group([], __DIR__ . '/web/marketingagreements.php');

// PAFs
Route::group([], __DIR__ . '/web/pricingadjustments.php');

// Inventory Removals
Route::group([], __DIR__ . '/web/inventoryremovals.php');

// Lookup Tables
Route::group([], __DIR__ . '/web/lookups/unitofmeasure.php');
Route::group([], __DIR__ . '/web/lookups/countries.php');
Route::group([], __DIR__ . '/web/lookups/currencies.php');
Route::group([], __DIR__ . '/web/lookups/brokers.php');

// Roles and Abilities
Route::group([], __DIR__ . '/web/roles/abilities.php');
Route::group([], __DIR__ . '/web/roles/roles.php');

// Promos
Route::group([], __DIR__ . '/web/promos.php');
// Route::group([], __DIR__.'/web/promos.php');

// Vendors
Route::group([], __DIR__ . '/web/vendors.php');

// Brands
Route::group([], __DIR__ . '/web/brands/disco-requests.php');
Route::group([], __DIR__ . '/web/brands/brand-finance.php');
Route::group([], __DIR__ . '/web/brands/brands.php');

// Products
Route::group([], __DIR__ . '/web/products/delist-requests.php');
Route::group([], __DIR__ . '/web/products/categories.php');
Route::group([], __DIR__ . '/web/products/products.php');

// Helpers
Route::group([], __DIR__ . '/web/address.php');

// Notifications
Route::group([], __DIR__ . '/web/notifications.php');

// Files
Route::group([], __DIR__ . '/web/files.php');

// Signoffs
Route::group([], __DIR__ . '/web/signoffs.php');

// Import/Exports
Route::group([], __DIR__ . '/web/imports.php');
Route::group([], __DIR__ . '/web/exports.php');

// Case Stack Deals
Route::group([], __DIR__ . '/web/casestackdeals.php');

// Quality Control
Route::prefix('qc')->middleware('auth')->group(function () {
    Route::get('/', [App\Http\Controllers\QualityControlController::class, 'index'])
        ->name('qc.index');
    Route::middleware('can:create,App\Models\QualityControlRecord')
        ->get('create', [App\Http\Controllers\QualityControlController::class, 'create'])
        ->name('qc.create');
    Route::get('{record}/edit', [App\Http\Controllers\QualityControlController::class, 'edit'])
        ->name('qc.edit')
        ->can('update', 'record');
    Route::get('{record}/labelling-form', \App\Http\Controllers\QualityControl\ShowLabellingForm::class)
        ->name('qc.labelling-form.show')
        ->can('view', 'record');
    Route::get('{record}/labelling-form/download', \App\Http\Controllers\QualityControl\DownloadLabellingForm::class)
        ->name('qc.labelling-form.download')
        ->can('view', 'record');

    Route::get('{record}/print/download', \App\Http\Controllers\QualityControl\DownloadPrintableForm::class)
        ->name('qc.print.download')
        ->can('view', 'record');
});

// Home Route
Route::middleware('auth')
    ->get('/', [\App\Http\Controllers\HomeController::class, 'index'])
    ->name('home');

Route::middleware('auth')->get('download-export', function (Request $request) {
        if (! $request->hasValidSignature()) {
            abort(401);
        }

        return response()->download($request->file, $request->filename);
    })->name('exports.download');
