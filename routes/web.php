<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::view('success', 'mercadopago.success')->name('mercadopago.success');
Route::view('failed', 'mercadopago.failed')->name('mercadopago.failed');