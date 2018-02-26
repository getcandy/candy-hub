<?php

namespace GetCandy\Providers;

use File;
use Illuminate\Support\ServiceProvider;

class PluginServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $loader = $this->app->make('composer_loader');

        $list = File::directories(realpath(__DIR__ . "/../../plugins"));

        foreach ($list as $dir) {
            $config = require($dir . '/candy.php');

            $namespace = "GetCandy\\Plugins\\" . $config['namespace_suffix'] . "\\";

            $loader->setPsr4($namespace, $dir . "/src/");

            $serviceProvider = $namespace . $config['service_provider'];

            $this->app->register($serviceProvider);
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
