<?php

namespace GetCandy\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use League\Fractal\Manager;
use Illuminate\Foundation\Http\Exceptions\MaintenanceModeException;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (\Request::ip() != '84.45.128.100') {
            throw new MaintenanceModeException(666, 666, 'No no no');
        }

        Validator::extend('valid_locales', 'GetCandy\Http\Validators\Api\LocaleValidator@validate');
        Validator::extend('enabled', 'GetCandy\Http\Validators\Api\BaseValidator@enabled');
        Validator::extend('asset_url', 'GetCandy\Http\Validators\Api\AssetValidator@validAssetUrl');
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

        $mediaDrivers = config('assets.upload_drivers');
        foreach ($mediaDrivers as $name => $driver) {
            $this->app->singleton($name . '.driver', function ($app) use ($driver) {
                return $app->make($driver);
            });
        }
    }
}
