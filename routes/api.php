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
 * Assets
 */

$this->put('assets', 'Assets\AssetController@updateAll');
$this->resource('assets', 'Assets\AssetController', [
    'except' => ['edit', 'create']
]);

/**
 * Associations
 */

$this->get('associations/groups', 'Associations\AssociationGroupController@index');
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
 * Categories
 */
$this->get('categories', 'Categories\CategoryController@index');
$this->get('categories/parent/{parentID?}', 'Categories\CategoryController@getByParent');
$this->post('categories/reorder', 'Categories\CategoryController@reorder');
$this->post('categories/create', 'Categories\CategoryController@store');
$this->get('categories/{id}', 'Categories\CategoryController@show');

/**
 * Channels
 */
$this->resource('channels', 'Channels\ChannelController', [
    'except' => ['edit', 'create', 'show']
]);

/**
 * Channels
 */
$this->resource('collections', 'Collections\CollectionController', [
    'except' => ['index', 'edit', 'create', 'show']
]);

/**
 * Customers
 */

$this->resource('customers/groups', 'Customers\CustomerGroupController', [
    'except' => ['edit', 'create', 'show']
]);



$this->resource('customers', 'Customers\CustomerController', [
    'except' => ['index', 'edit', 'create', 'show', 'store']
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
 * Product variants
 */
$this->resource('products/variants', 'Products\ProductVariantController', [
    'except' => ['edit', 'create', 'store']
]);
$this->post('products/{product}/variants', 'Products\ProductVariantController@store');

/**
 * Products
 */
$this->post('products/{product}/urls', 'Products\ProductController@createUrl');
$this->post('products/{product}/redirects', 'Products\ProductRedirectController@store');
$this->post('products/{product}/attributes', 'Products\ProductAttributeController@update');
$this->post('products/{product}/collections', 'Products\ProductCollectionController@update');
$this->post('products/{product}/routes', 'Products\ProductRouteController@update');
$this->post('products/{product}/categories', 'Products\ProductCategoryController@update');
$this->delete('products/{product}/categories/{category}', 'Products\ProductCategoryController@destroy');
$this->post('products/{product}/associations', 'Products\ProductAssociationController@store');
$this->resource('products', 'Products\ProductController', [
    'except' => ['edit', 'create']
]);

/**
 * Product families
 */
$this->resource('product-families', 'Products\ProductFamilyController', [
    'except' => ['edit', 'create']
]);

/**
 * Routes
 */
$this->resource('routes', 'Routes\RouteController', [
    'except' => ['index', 'show', 'edit', 'create']
]);

$this->get('search/internal', 'Search\SearchController@internal');

/**
 * Tags
 */
$this->resource('tags', 'Tags\TagController', [
    'except' => ['edit', 'create']
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
