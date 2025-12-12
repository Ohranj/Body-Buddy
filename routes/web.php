<?php

use App\Http\Controllers\CaloriesController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => ['auth']], function () {
    Route::get('/dashboard', DashboardController::class);
    Route::resource('/calories', CaloriesController::class);
});

Route::inertia('/test', 'Test');
