<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {

    Route::get('users/filter', [UserController::class, 'filter']);
    
    Route::apiResource('users', UserController::class)
        ->only(['show', 'update', 'destroy'])->except('store');

    Route::apiResource('users', UserController::class)
        ->except(['show', 'store', 'update', 'destroy'])
        ->middleware('is_admin');

});
