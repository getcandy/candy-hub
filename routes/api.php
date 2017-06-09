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
 * Channels
 */
$this->resource('channels', 'ChannelController', [
    'except' => ['index', 'edit', 'create']
]);


/**
 * Products
 */
$this->resource('products', 'ProductController', [
    'except' => ['index', 'edit', 'create']
]);

/**
 * Product families
 */
$this->resource('product-families', 'ProductFamilyController', [
    'except' => ['index', 'edit', 'create']
]);

/**
 * Attribute Groups
 */
$this->put('attribute-groups/order', 'AttributeGroupController@reorder');
$this->resource('attribute-groups', 'AttributeGroupController', [
    'except' => ['edit', 'create']
]);

/**
 * Attributes
 */
$this->put('attributes/order', 'AttributeController@reorder');
$this->resource('attributes', 'AttributeController', [
    'except' => ['edit', 'create']
]);

/**
 * Languages
 */
$this->resource('languages', 'LanguageController', [
    'except' => ['edit', 'create']
]);

/**
 * Currencies
 */
$this->resource('currencies', 'CurrencyController', [
    'except' => ['edit', 'create']
]);

/**
 * Taxes
 */
$this->resource('taxes', 'TaxController', [
    'except' => ['edit', 'create']
]);

/**
 * Plugins
 */
$this->post('/plugins/install', 'PluginController@postInstall');

/**
 * Users
 */
$this->get('users/current', 'UserController@getCurrentUser');
$this->resource('users', 'UserController', [
    'except' => ['edit', 'create']
]);

/**
 * Pages
 */
$this->get('/pages/{channel}/{lang}/{slug?}', 'PageController@show');
$this->resource('pages', 'PageController', [
    'except' => ['edit', 'create']
]);

/**
 * Layouts
 */
$this->resource('layouts', 'LayoutController', [
    'except' => ['edit', 'create']
]);
