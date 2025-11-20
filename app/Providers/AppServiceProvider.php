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
        // Force HTTPS for all URLs in production or when request is HTTPS
        if (app()->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        } elseif (request() && (request()->getScheme() === 'https' || request()->header('X-Forwarded-Proto') === 'https')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
        
        // Ensure asset URLs use HTTPS when in production
        if (app()->environment('production')) {
            \Illuminate\Support\Facades\URL::forceRootUrl(config('app.url'));
        }
    }
}
