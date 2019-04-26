<?php

namespace GetCandy\Hub\Providers;

use GetCandy\Hub\Http\Middleware\Access;
use GetCandy\Hub\Http\Middleware\Authenticated;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Http\Middleware\CreateFreshApiToken;
use View;

class HubServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', 'hub');

        $this->registerViewComposers();
        $this->registerBladeDirectives();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadRoutesFrom(__DIR__.'/../../routes/hub.php');
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'hub');

        $this->app['router']->aliasMiddleware('hub.refresh_token', CreateFreshApiToken::class);
        $this->app['router']->aliasMiddleware('hub.access', Access::class);
        $this->app['router']->aliasMiddleware('hub.auth', Authenticated::class);

        $this->publishes([
            __DIR__.'/../../resources/build' => public_path('candy-hub'),
        ], 'public');
    }

    protected function registerViewComposers()
    {
        View::composer('hub::partials.head', 'GetCandy\Hub\Http\ViewComposers\Partials\HeadComposer');
        View::composer('hub::partials.scripts', 'GetCandy\Hub\Http\ViewComposers\Partials\ScriptsComposer');
        View::composer('hub::partials.top-menu', 'GetCandy\Hub\Http\ViewComposers\Partials\TopMenuComposer');

        View::composer('hub::catalogue-manager.partials.side-menu', 'GetCandy\Hub\Http\ViewComposers\CatalogueManager\Partials\SideMenuComposer');
        View::composer('hub::order-processing.partials.side-menu', 'GetCandy\Hub\Http\ViewComposers\OrderProcessing\Partials\SideMenuComposer');
        View::composer('hub::marketing-suite.partials.side-menu', 'GetCandy\Hub\Http\ViewComposers\MarketingSuite\Partials\SideMenuComposer');
        View::composer('hub::reports.partials.side-menu', 'GetCandy\Hub\Http\ViewComposers\Reports\Partials\SideMenuComposer');

        View::composer('hub::layout', 'GetCandy\Hub\Http\ViewComposers\Plugins\PluginResourceComposer');
    }

    protected function registerBladeDirectives()
    {
        Blade::directive('channel', function ($expression) {
            $channel = app('api')->channels()->getDefaultRecord();

            return "<?php echo '{$channel->handle}' ?>";
        });
    }
}
