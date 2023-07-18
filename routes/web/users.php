<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserSubmissionsController;
use Illuminate\Support\Facades\Route;

// Users
Route::prefix('users')->middleware('can:view,App\User')->group(function () {
    // Create
    Route::middleware('can:create,App\User')->group(function () {
        Route::get('create', [\App\Http\Controllers\UserController::class, 'create'])->name('users.create');
        Route::post('/', [\App\Http\Controllers\UserController::class, 'store'])->name('users.store');
    });

    // Read
    Route::get('/', [\App\Http\Controllers\UserController::class, 'index'])->name('users.index');
    Route::get('{id}', [\App\Http\Controllers\UserController::class, 'show'])->name('users.show');

    // Impersonation
    Route::middleware('can:impersonate-users')->get('impersonate/{user}', [\App\Http\Controllers\UserController::class, 'impersonate'])->name('users.impersonate');

    // Update
    Route::middleware('can:edit,App\User')->group(function () {
        Route::get('{id}/edit', [\App\Http\Controllers\UserController::class, 'edit'])->name('users.edit');
        Route::patch('{id}', [\App\Http\Controllers\UserController::class, 'update'])->name('users.update');
    });

    // Delete
    Route::middleware('can:delete,App\User')->delete('{id}', [\App\Http\Controllers\UserController::class, 'destroy'])->name('users.delete');
});

// Profile
Route::prefix('profile')->middleware('auth')->group(function () {
    Route::view('change-password', 'users.profile.change-password')->name('profile.change-password');
    // Create

    // Read
    Route::get('submissions/{filter?}', UserSubmissionsController::class)->name('user.submissions');

    // Update
    Route::get('edit', [ProfileController::class, 'edit'])->name('profile.edit');

    // Delete
});

Route::impersonate();
