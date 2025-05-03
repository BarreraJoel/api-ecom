<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'is_admin'])->group(function () {
    Route::apiResource('categories', CategoryController::class);
});
