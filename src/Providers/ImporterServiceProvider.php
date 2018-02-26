<?php

namespace GetCandy\Hub\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use League\Fractal\Manager;
use GetCandy\Importers\Aqua\Factory as AquaSpaSupplies;

class ImporterServiceProvider extends ServiceProvider
{
    protected $importers = [
        'aqua' => AquaSpaSupplies::class
    ];

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

        foreach ($this->importers as $name => $driver) {
            $this->app->singleton($name . '.importer', function ($app) use ($driver) {
                return $app->make($driver);
            });
        }
    }
}
