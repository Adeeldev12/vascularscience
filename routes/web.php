<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScientistAuthController;
use App\Http\Controllers\ScientistRegisterController;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/admin/register', [ScientistRegisterController::class, 'show'])
//     ->name('scientist.register')
//     ->middleware('guest');

// Route::post('/admin/register', [ScientistRegisterController::class, 'register'])
//     ->name('scientist.register.submit')
//     ->middleware('guest');
