<?php

namespace App\Providers;

use App\Auth\Guards\CustomTokenGuard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any authentication / authorization services.
     */
    public function register(): void
    {
        // You may include bindings or singletons here if required.
    }

    /**
     * Bootstrap any authentication / authorization services.
     */
    public function boot(): void
    {
        // Register custom authentication guard
        Auth::extend('custom-token', function ($app, $name, array $config) {
            return new CustomTokenGuard($app['request']);
        });
    }
}
