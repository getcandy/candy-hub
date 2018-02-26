<?php

namespace GetCandy\Providers;

use GetCandy\Api\Factory;
use Illuminate\Support\ServiceProvider;
use Validator;

class ApiServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('unique_name_in_group', 'GetCandy\Http\Validators\Api\AttributeValidator@uniqueNameInGroup');
        Validator::extend('hashid_is_valid', 'GetCandy\Http\Validators\Api\HashidValidator@validForModel');
        Validator::extend('valid_structure', 'GetCandy\Http\Validators\Api\AttributeValidator@validateData');
        Validator::extend('unique_category_attribute', 'GetCandy\Http\Validators\Api\CategoriesValidator@uniqueCategoryAttributeData');
        Validator::extend('unique_route', 'GetCandy\Http\Validators\Api\RoutesValidator@uniqueRoute');
        Validator::extend('check_coupon', 'GetCandy\Api\Discounts\Validators\DiscountValidator@checkCoupon');

        $this->app->bind(\GetCandy\Api\Shipping\ShippingCalculator::class, function ($app) {
            return $app->make(\GetCandy\Api\Shipping\ShippingCalculator::class);
        });

        $this->app->singleton('api', function ($app) {
            return $app->make(Factory::class);
        });

        $this->app->bind('assets.driver', function ($app) {

        });

        $this->mapApiRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {

        $apiPrefix = 'api/' . config('app.api_version', 'v1');
        $namespace = 'GetCandy\Http\Controllers\Api';

        Route::middleware(['auth:api', 'api.language', 'customer_groups'])
            ->namespace('GetCandy\Http\Controllers\Api')
            ->prefix($apiPrefix)
            ->group(__DIR__ . '../../routes/api.php');

        Route::middleware(['api:client', 'api.language', 'customer_groups'])
            ->namespace('GetCandy\Http\Controllers\Api')
            ->prefix($apiPrefix)
            ->group(__DIR__ . '../../routes/api.client.php');
        // Route::prefix('api')
        //      ->middleware('api')
        //      ->namespace($this->namespace)
        //      ->group(base_path('routes/api.php'));
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
}
