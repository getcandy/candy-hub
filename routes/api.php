<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/**
 * Attributes
 */
$this->put('attributes/order', 'Attributes\AttributeController@reorder');
$this->resource('attributes', 'Attributes\AttributeController', [
    'except' => ['edit', 'create']
]);

/**
 * Attribute Groups
 */
$this->put('attribute-groups/order', 'Attributes\AttributeGroupController@reorder');
$this->resource('attribute-groups', 'Attributes\AttributeGroupController', [
    'except' => ['edit', 'create']
]);

/**
 * Channels
 */
$this->resource('channels', 'Channels\ChannelController', [
    'except' => ['index', 'edit', 'create', 'show']
]);

/**
 * Channels
 */
$this->resource('collections', 'Collections\CollectionController', [
    'except' => ['index', 'edit', 'create', 'show']
]);


/**
 * Currencies
 */
$this->resource('currencies', 'Currencies\CurrencyController', [
    'except' => ['edit', 'create']
]);

/**
 * Languages
 */
$this->resource('languages', 'Languages\LanguageController', [
    'except' => ['edit', 'create']
]);

/**
 * Layouts
 */
$this->resource('layouts', 'Layouts\LayoutController', [
    'except' => ['edit', 'create']
]);

/**
 * Pages
 */
$this->get('/pages/{channel}/{lang}/{slug?}', 'Pages\PageController@show');
$this->resource('pages', 'Pages\PageController', [
    'except' => ['edit', 'create']
]);

/**
 * Products
 */
$this->post('products/{product}/attributes', 'Products\ProductController@updateAttributes');
$this->post('products/{product}/collections', 'Products\ProductController@updateCollections');
$this->post('products/{product}/variants', 'Products\ProductController@createVariants');
$this->post('products/{product}/routes', 'Products\ProductController@updateRoutes');
$this->resource('products', 'Products\ProductController', [
    'except' => ['edit', 'create']
]);

/**
 * Product families
 */
$this->resource('product-families', 'Products\ProductFamilyController', [
    'except' => ['index', 'edit', 'create']
]);

/**
 * Routes
 */
$this->resource('routes', 'Routes\RouteController', [
    'except' => ['index', 'show', 'edit', 'create']
]);


/**
 * Taxes
 */
$this->resource('taxes', 'Taxes\TaxController', [
    'except' => ['edit', 'create']
]);

/**
 * Users
 */
$this->get('users/current', 'Users\UserController@getCurrentUser');
$this->resource('users', 'Users\UserController', [
    'except' => ['edit', 'create']
]);
