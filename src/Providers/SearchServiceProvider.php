<?php

namespace GetCandy\Providers;

use Illuminate\Support\ServiceProvider;
use GetCandy\Search\SearchContract;

class SearchServiceProvider extends ServiceProvider
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
        $this->app->singleton(SearchContract::class, function ($app) {
            return $app->make(config('search.client'));
        });
    }
}
