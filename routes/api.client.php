<?php

/*
|--------------------------------------------------------------------------
| API Client Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API Client routes for GetCandy
| These are READ ONLY routes
|
*/
// $this->get('channels', 'Channels\ChannelController@index');
$this->get('channels/{id}', 'Channels\ChannelController@show');
$this->get('collections', 'Collections\CollectionController@index');
$this->get('collections/{id}', 'Collections\CollectionController@show');
$this->get('categories/{id}', 'Categories\CategoryController@show');
$this->get('products/{product}', 'Products\ProductController@show');
$this->post('customers', 'Customers\CustomerController@store');
$this->get('products', 'Products\ProductController@index');



/**
 * Baskets
 */
$this->get('baskets', 'Products\ProductController@index');
$this->resource('baskets', 'Baskets\BasketController', [
    'except' => ['edit', 'create']
]);

/**
 * Categories
 */
$this->get('categories', 'Categories\CategoryController@index');

/**
 * Countries
 */
$this->get('countries', 'Countries\CountryController@index');

/**
 * Currencies
 */
$this->resource('currencies', 'Currencies\CurrencyController', [
    'except' => ['edit', 'create']
]);

/**
 * Customers
 */
$this->resource('customers', 'Customers\CustomerController', [
    'except' => ['index', 'edit', 'create', 'show']
]);

/**
 * Orders
 */

$this->post('orders/process', 'Orders\OrderController@process');
$this->post('orders/{id}/expire', 'Orders\OrderController@expire');
$this->put('orders/{id}/shipping/address', 'Orders\OrderController@shippingAddress');
$this->put('orders/{id}/shipping/methods', 'Orders\OrderController@shippingMethod');
$this->get('orders/{id}/shipping/methods', 'Orders\OrderController@shippingMethods');
$this->put('orders/{id}/shipping/price', 'Orders\OrderController@shippingPrice');
$this->put('orders/{id}/billing/address', 'Orders\OrderController@billingAddress');
$this->resource('orders', 'Orders\OrderController', [
    'only' => ['store', 'show']
]);

/**
 * Payments
 */
$this->get('payments/provider', 'Payments\PaymentController@provider');

$this->get('routes', 'Routes\RouteController@index');
$this->get('routes/{slug}', [
    'uses' => 'Routes\RouteController@show'
])->where(['slug' => '.*']);


$this->post('password/reset', 'Auth\ResetPasswordController@reset');
$this->post('password/reset/request', 'Auth\ForgotPasswordController@sendResetLinkEmail');

$this->get('search', 'Search\SearchController@search');
$this->get('search/products', 'Search\SearchController@products');

/**
 * Shipping
 */
$this->get('shipping', 'Shipping\ShippingMethodController@index');
