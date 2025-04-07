<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {

    Route::apiResource('products', ProductController::class);
    Route::get('filter', [ProductController::class, 'filter']);
    
});
