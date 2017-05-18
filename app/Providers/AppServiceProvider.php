<?php

namespace GetCandy\Providers;

use Illuminate\Support\ServiceProvider;
use League\Fractal\Manager;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('fractal', function ($app) {
            return new Manager();
        });
    }
}
