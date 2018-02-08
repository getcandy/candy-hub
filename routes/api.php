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
 * Account
 */

 $this->post('account/password', [
     'as' => 'account.password.reset',
     'uses' => 'Auth\AccountController@resetPassword'
 ]);

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
 * Baskets
 */
$this->post('baskets/resolve', 'Baskets\BasketController@resolve');
$this->get('baskets/current', 'Baskets\BasketController@current');

/**
 * Payments
 */
$this->post('payments/{id}/refund', 'Payments\PaymentController@refund');
$this->post('payments/{id}/void', 'Payments\PaymentController@void');

/**
 * Categories
 */
$this->get('categories/parent/{parentID?}', 'Categories\CategoryController@getByParent');
$this->post('categories/reorder', 'Categories\CategoryController@reorder');

$this->post('categories/{category}/routes', 'Categories\CategoryRouteController@store');
$this->resource('categories', 'Categories\CategoryController', [
    'except' => ['index', 'edit', 'create', 'show']
]);

/**
 * Channels
 */
$this->resource('channels', 'Channels\ChannelController', [
    'except' => ['edit', 'create', 'show']
]);

/**
 * Channels
 */
$this->post('collections/{collection}/routes', 'Collections\CollectionRouteController@store');
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
    'except' => ['edit', 'create', 'store']
]);

/**
 * Discounts
 */
$this->resource('discounts', 'Discounts\DiscountController', [
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
 * Orders
 */
$this->get('orders/{id}/invoice', 'Orders\OrderController@invoice');
$this->resource('orders', 'Orders\OrderController', [
    'only' => ['index', 'update']
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
$this->post('products/{product}/routes', 'Products\ProductRouteController@store');
$this->post('products/{product}/categories', 'Products\ProductCategoryController@update');
$this->delete('products/{product}/categories/{category}', 'Products\Produ\ctCategoryController@destroy');
$this->delete('products/{product}/collections/{collection}', 'Products\ProductCollectionController@destroy');
$this->post('products/{product}/associations', 'Products\ProductAssociationController@store');
$this->delete('products/{product}/associations', 'Products\ProductAssociationController@destroy');
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

/**
 * Saved search
 */
$this->post('saved-searches', 'Search\SavedSearchController@store');
$this->delete('saved-searches/{id}', 'Search\SavedSearchController@destroy');
$this->get('saved-searches/{type}', 'Search\SavedSearchController@getByType');

/**
 * Shipping
 */
$this->resource('shipping/zones', 'Shipping\ShippingZoneController', [
    'except' => ['edit', 'create']
]);
$this->post('shipping/{id}/prices', 'Shipping\ShippingPriceController@store');
$this->delete('shipping/prices/{id}', 'Shipping\ShippingPriceController@destroy');
$this->put('shipping/prices/{id}', 'Shipping\ShippingPriceController@update');
$this->put('shipping/{id}/zones', 'Shipping\ShippingMethodController@updateZones');
$this->put('shipping/{id}/users', 'Shipping\ShippingMethodController@updateUsers');
$this->delete('shipping/{id}/users/{user}', 'Shipping\ShippingMethodController@deleteUser');
$this->resource('shipping', 'Shipping\ShippingMethodController', [
    'except' => ['index', 'edit', 'create']
]);

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
    'except' => ['create', 'store']
]);