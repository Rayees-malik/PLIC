<?php

use Illuminate\Support\Facades\Route;

Route::prefix('files')->middleware('auth')->group(function () {
    // Created
    Route::post('upload', [\App\Http\Controllers\FileController::class, 'upload'])->name('files.upload');
});
