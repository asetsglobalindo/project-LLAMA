<?php

namespace App\Providers;

use App\Services\NewsApiService;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(NewsApiService::class, function ($app) {
            return new NewsApiService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // if (config('app.env') === 'production') {
        //     URL::forceScheme('https');
        // }
    }
}
