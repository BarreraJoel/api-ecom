<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {

    Route::get('products/filter', [ProductController::class, 'filter']);
    
    Route::apiResource('products', ProductController::class)
        ->only(['index', 'show']);
        
    Route::apiResource('products', ProductController::class)
        ->except(['index', 'show'])
        ->middleware('is_admin');

});
