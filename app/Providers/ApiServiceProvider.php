<?php

namespace GetCandy\Providers;

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
        Validator::extend('unique_name_in_group', 'GetCandy\Api\Http\Validation\AttributeValidation@uniqueNameInGroup');
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
