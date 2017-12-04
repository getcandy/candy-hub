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

$this->get('/', function () {
    return redirect()->route('login');
});


Route::get('foo', function () {
    $this->importer = app('aqua.importer');

    $products = $this->importer->getProducts();

    dd($products);
});
// Authentication Routes...
$this->group(['namespace' => 'Cms\\Auth'], function () {
    $this->get('login', 'LoginController@showLoginForm')->name('login');
    $this->post('login', 'LoginController@login');
    $this->get('logout', 'LoginController@logout')->name('logout');

    $this->get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.request');
    $this->post('password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    $this->get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');
    $this->post('password/reset', 'ResetPasswordController@reset');
});

/**
 * Hub Routes
 */
$this->group(['middleware' => ['hub', 'auth']], function () {

    $this->get('dashboard', [
        'as' => 'hub.index',
        'uses' => 'Cms\DashboardController@getIndex'
    ]);

    /**
     * Catalogue manager routes
     */
    $this->group(['prefix' => 'catalogue-manager', 'namespace' => 'Cms\\CatalogueManager'], function () {
        $this->get('products', [
            'as' => 'hub.products.index',
            'uses' => 'ProductsController@getIndex'
        ]);
        $this->get('products/{id}', [
            'as' => 'hub.products.edit',
            'uses' => 'ProductsController@getEdit'
        ]);
        $this->get('collections', [
            'as' => 'hub.collections.index',
            'uses' => 'CollectionsController@getIndex'
        ]);
        $this->get('collections/{id}', [
            'as' => 'hub.collections.edit',
            'uses' => 'CollectionsController@getEdit'
        ]);
        $this->get('categories', [
            'as' => 'hub.categories.index',
            'uses' => 'CategoriesController@getIndex'
        ]);
        $this->get('categories/{id}', [
            'as' => 'hub.categories.edit',
            'uses' => 'CategoriesController@getEdit'
        ]);
    });
});

