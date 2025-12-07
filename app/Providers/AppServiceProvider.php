<?php

namespace App\Providers;

use App\Services\CustomGuard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Auth::extend('custom', function ($app, $name, array $config) {
            return new CustomGuard(Auth::createUserProvider($config['provider']));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {}
}
