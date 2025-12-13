<?php

use App\Http\Controllers\CaloriesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\AddTimestampToRequest;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => ['auth', AddTimestampToRequest::class]], function () {
    Route::get('/dashboard', DashboardController::class);
    Route::get('/profile', ProfileController::class);
    Route::resource('/calories', CaloriesController::class);
});

Route::inertia('/test', 'Test');
