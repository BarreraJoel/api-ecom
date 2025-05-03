<?php

use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'is_admin'])->group(function () {
    Route::apiResource('roles', RoleController::class);
});
