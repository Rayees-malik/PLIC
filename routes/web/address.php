<?php

Route::prefix("address")
    ->middleware("auth")
    ->group(function () {
        // Create
        Route::get("create", [
            \App\Http\Controllers\AddressController::class,
            "create",
        ])->name("address.create");
        Route::post("/", [
            \App\Http\Controllers\AddressController::class,
            "store",
        ])->name("address.store");

        // Read

        // Update
        Route::patch("{id}", [
            \App\Http\Controllers\AddressController::class,
            "update",
        ])->name("address.update");

        // Delete
        Route::delete("{id}", [
            \App\Http\Controllers\AddressController::class,
            "destroy",
        ])->name("address.delete");
    });
