<?php

namespace GetCandy\Hub\Providers;

use Illuminate\Support\ServiceProvider;
use Validator;
use View;
use Laravel\Passport\Http\Middleware\CreateFreshApiToken;
use GetCandy\Hub\Http\Middleware\HubAccess;

class HubServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerViewComposers();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadRoutesFrom(__DIR__ . '/../../routes/hub.php');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'hub');

        $this->app['router']->aliasMiddleware('hub.refresh_token', CreateFreshApiToken::class);
        $this->app['router']->aliasMiddleware('hub.access', CreateFreshApiToken::class);

    }

    protected function registerViewComposers()
    {
        View::composer('partials.head', 'GetCandy\Http\ViewComposers\Partials\HeadComposer');
        View::composer('partials.scripts', 'GetCandy\Http\ViewComposers\Partials\ScriptsComposer');
        View::composer('partials.top-menu', 'GetCandy\Http\ViewComposers\Partials\TopMenuComposer');

        View::composer('catalogue-manager.partials.side-menu', 'GetCandy\Http\ViewComposers\CatalogueManager\Partials\SideMenuComposer');
        View::composer('order-processing.partials.side-menu', 'GetCandy\Http\ViewComposers\OrderProcessing\Partials\SideMenuComposer');
        View::composer('marketing-suite.partials.side-menu', 'GetCandy\Http\ViewComposers\MarketingSuite\Partials\SideMenuComposer');
    }
}
