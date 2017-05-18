<?php

namespace GetCandy\Providers;

use Illuminate\Support\ServiceProvider;
use Validator;

class CmsServiceProvider extends ServiceProvider
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
        //
    }

    protected function registerViewComposers()
    {
        View::composer('partials.head', 'GetCandy\Http\ViewComposers\Partials\HeadComposer');
        View::composer('partials.scripts', 'GetCandy\Http\ViewComposers\Partials\ScriptsComposer');
        View::composer('partials.top-menu', 'GetCandy\Http\ViewComposers\Partials\TopMenuComposer');

        View::composer('catalogue-manager.partials.side-menu', 'GetCandy\Http\ViewComposers\CatalogueManager\Partials\SideMenuComposer');
    }
}
