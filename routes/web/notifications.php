<?php

use Illuminate\Support\Facades\Route;

Route::prefix('notifications')->middleware('auth')->group(function () {
    // Create

    // Read
    Route::get('{filter?}', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('archived', [\App\Http\Controllers\NotificationController::class, 'archived'])->name('notifications.archived');

    // Update
    Route::post('{id}/dismiss', [\App\Http\Controllers\NotificationController::class, 'dismiss'])->name('notifications.dismiss');
    Route::get('{id}/read', [\App\Http\Controllers\NotificationController::class, 'read'])->name('notifications.read');

    // Delete
});
