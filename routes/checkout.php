<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Middleware\CartIsNotEmptyMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('checkout')->middleware(CartIsNotEmptyMiddleware::class)->group(function () {
        Route::get('stripe', [CheckoutController::class, 'checkoutStripe']);
        Route::get('mercado_pago', [CheckoutController::class, 'checkoutMp']);
    });
});

Route::post('receive_pay', [CheckoutController::class, 'receivePay']);
