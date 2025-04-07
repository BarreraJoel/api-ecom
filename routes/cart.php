<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('cart')->group(function () {

        Route::get('items', [CartController::class, 'listItems']);
        Route::post('add_item', [CartController::class, 'addItem']);
        Route::post('remove_item', [CartController::class, 'removeItem']);
        Route::get('empty', [CartController::class, 'empty']);
    });

    Route::get('checkout', [CheckoutController::class, 'checkout']);
    Route::get('checkout_mp', [CheckoutController::class, 'checkoutMp']);
    
});

Route::post('receive-pay', [CheckoutController::class, 'receivePay']);
