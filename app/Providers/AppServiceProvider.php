<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\WalletService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(WalletService::class, function ($app) {
            return new WalletService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
