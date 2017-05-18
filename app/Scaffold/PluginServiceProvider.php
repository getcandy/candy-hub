<?php

namespace GetCandy\Scaffold;

use Illuminate\Support\ServiceProvider;

abstract class PluginServiceProvider extends ServiceProvider
{
    protected $migrations = [];

    protected $routeFiles = [];

    protected $viewDirs = [];

    protected $listen = [];

    protected $subscribe = [];

    public function boot()
    {
        foreach ($this->migrations as $path) {
            $this->loadMigrationsFrom($path);
        }

        foreach ($this->routeFiles as $file) {
            $this->loadRoutesFrom($file);
        }

        foreach ($this->viewDirs as $namespace => $dir) {
            $this->loadViewsFrom($dir, $namespace);
        }

        $events = app('events');

        foreach ($this->listen as $event => $listeners) {
            foreach ($listeners as $listener) {
                $events->listen($event, $listener);
            }
        }

        foreach ($this->subscribe as $subscriber) {
            $events->subscribe($subscriber);
        }
    }
}
