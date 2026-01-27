<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Kalau ada binding custom, bisa ditaruh di sini
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Kalau ada boot logic global, bisa ditaruh di sini
    }
}
