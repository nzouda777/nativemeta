<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;

class StripeServiceProvider extends ServiceProvider
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
        // Configurer dynamiquement les clés Stripe selon le mode
        $mode = config('services.stripe.mode', 'test');
        
        Config::set('services.stripe.key', config("services.stripe.{$mode}.key"));
        Config::set('services.stripe.secret', config("services.stripe.{$mode}.secret"));
        Config::set('services.stripe.webhook_secret', config("services.stripe.{$mode}.webhook_secret"));
    }
}
