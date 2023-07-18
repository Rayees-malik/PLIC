<?php

use Illuminate\Support\Facades\Route;

// Signoffs
Route::prefix('signoffs')->middleware('auth')->group(function () {
    // Management Bulk Approval
    Route::prefix('management')->middleware('can:signoff.product.management')->group(function () {
        Route::get('/', [\App\Http\Controllers\SignoffController::class, 'management'])->name('signoffs.management');
        Route::post('/', [\App\Http\Controllers\SignoffController::class, 'managementUpdate'])->name('signoffs.management.update');
        Route::get('{brandId}', [\App\Http\Controllers\SignoffController::class, 'managementReview'])->name('signoffs.management.review');
    });

    // Finance Bulk Approval
    Route::prefix('finance')->middleware('can:signoff.product.finance')->group(function () {
        Route::get('/', [\App\Http\Controllers\SignoffController::class, 'financeReview'])->name('signoffs.finance.review');
        Route::post('/', [\App\Http\Controllers\SignoffController::class, 'financeUpdate'])->name('signoffs.finance.update');
    });

    // Webseries Bulk Approval
    Route::prefix('webseries')->middleware('can:signoff.webseries')->group(function () {
        Route::get('/', [\App\Http\Controllers\SignoffController::class, 'webseriesReview'])->name('signoffs.webseries.review');
        Route::post('/', [\App\Http\Controllers\SignoffController::class, 'webseriesUpdate'])->name('signoffs.webseries.update');
        Route::post('export', [\App\Http\Controllers\SignoffController::class, 'webseriesBulkExport'])->name('signoffs.webseries.export');
    });

    // Create
    Route::middleware('can:signoff')->get('/', [\App\Http\Controllers\SignoffController::class, 'index', 'middleware' => 'can:signoff'])->name('signoffs.index');
    Route::get('{id}', [\App\Http\Controllers\SignoffController::class, 'show'])->name('signoffs.show');

    // Update
    Route::middleware('can:signoff')->group(function () {
        Route::get('{id}/edit', [\App\Http\Controllers\SignoffController::class, 'edit'])->name('signoffs.edit');
        Route::post('{id}/save', [\App\Http\Controllers\SignoffController::class, 'update'])->name('signoffs.update');
    });
    Route::get('{id}/unsubmit', [\App\Http\Controllers\SignoffController::class, 'unsubmit'])->name('signoffs.unsubmit');

    // Delete
    Route::delete('{id}/delete', [\App\Http\Controllers\SignoffController::class, 'destroy'])->name('signoffs.delete');
});
