<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\Auth\AuthenticateController;
use App\Http\Controllers\Auth\RegisterController;

Route::group(['middleware' => 'guest'], function () {
    Route::get('/', WelcomeController::class)->name('login');
    Route::post('/login', [AuthenticateController::class, 'store']);
    Route::resource('/register', RegisterController::class)->only(['index', 'store']);
});
