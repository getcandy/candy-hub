<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group([
    'prefix' => 'hub',
    'namespace' => 'GetCandy\Hub\Http\Controllers',
    'middleware' => ['web'],
], function ($router) {

    $router->get('/', function () {
        return redirect()->route('login');
    });

    // Authentication Routes...
    $router->group(['namespace' => 'Auth'], function ($router) {
        $router->get('login', 'LoginController@showLoginForm')->name('login');
        $router->post('login', 'LoginController@login');
        $router->get('logout', 'LoginController@logout')->name('logout');

        $router->get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.request');
        $router->post('password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
        $router->get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');
        $router->post('password/reset', 'ResetPasswordController@reset');
    });

    /**
     * Hub Routes
     */
    $router->group(['middleware' => ['auth', 'hub.refresh_token', 'hub.access']], function ($router) {

        $router->get('dashboard', [
            'as' => 'hub.index',
            'uses' => 'DashboardController@getIndex'
        ]);

        /**
         * Catalogue manager routes
         */
        $router->group(['prefix' => 'catalogue-manager', 'namespace' => 'CatalogueManager'], function ($router) {
            $router->get('products', [
                'as' => 'hub.products.index',
                'uses' => 'ProductsController@getIndex'
            ]);
            $router->get('products/{id}', [
                'as' => 'hub.products.edit',
                'uses' => 'ProductsController@getEdit'
            ]);
            $router->get('collections', [
                'as' => 'hub.collections.index',
                'uses' => 'CollectionsController@getIndex'
            ]);
            $router->get('collections/{id}', [
                'as' => 'hub.collections.edit',
                'uses' => 'CollectionsController@getEdit'
            ]);
            $router->get('categories', [
                'as' => 'hub.categories.index',
                'uses' => 'CategoriesController@getIndex'
            ]);
            $router->get('categories/{id}', [
                'as' => 'hub.categories.edit',
                'uses' => 'CategoriesController@getEdit'
            ]);
        });

        $router->group(['prefix' => 'order-processing', 'namespace' => 'OrderProcessing'], function ($router) {
            $router->get('orders', [
                'as' => 'hub.orders.index',
                'uses' => 'OrderController@getIndex'
            ]);
            $router->get('orders/{id}', [
                'as' => 'hub.orders.edit',
                'uses' => 'OrderController@getEdit'
            ]);
            $router->get('orders/{id}/invoice', [
                'as' => 'hub.orders.invoice',
                'uses' => 'OrderController@invoice'
            ]);
            $router->get('shipping-methods', [
                'as' => 'hub.shipping.index',
                'uses' => 'ShippingController@getIndex'
            ]);
            $router->get('shipping-methods/{id}', [
                'as' => 'hub.shipping.edit',
                'uses' => 'ShippingController@getEdit'
            ]);

            $router->get('shipping-zones', [
                'as' => 'hub.shipping.zones',
                'uses' => 'ShippingController@getZones'
            ]);
            $router->get('shipping-zones/{id}', [
                'as' => 'hub.shipping.zones.edit',
                'uses' => 'ShippingController@getZone'
            ]);

        // Customer routes
            $router->get('customers', [
                'as' => 'hub.customers.index',
                'uses' => 'CustomerController@getIndex'
            ]);
            $router->get('customers/{id}', [
                'as' => 'hub.customers.view',
                'uses' => 'CustomerController@getShow'
            ]);

        });

        $router->group(['prefix' => 'marketing-suite', 'namespace' => 'MarketingSuite'], function ($router) {
            $router->get('discounts', [
                'as' => 'hub.discounts.index',
                'uses' => 'DiscountController@getIndex'
            ]);
            $router->get('discounts/{id}', [
                'as' => 'hub.discounts.edit',
                'uses' => 'DiscountController@getEdit'
            ]);
        });
    });
});


