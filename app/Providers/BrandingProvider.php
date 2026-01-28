<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class BrandingProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // La inyección del logo se realiza mediante JavaScript en filament-logo.js
    }
}
