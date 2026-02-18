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
    //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS in production environments
        if ($this->app->environment('production', 'staging')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // Suppress deprecation warnings (e.g. from vendor files on PHP 8.5)
        error_reporting(E_ALL & ~E_DEPRECATED);
    }
}
