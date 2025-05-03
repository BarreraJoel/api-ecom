<?php

use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::delete('cart/items', [CartController::class, 'destroy_all']);
    Route::apiResource('cart/items', CartController::class)
        ->except('show');
});
