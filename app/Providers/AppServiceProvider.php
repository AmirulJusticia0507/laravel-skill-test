<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind service custom jika ada
    }

    public function boot(): void
    {
        // Boot logic global jika ada
    }
}
