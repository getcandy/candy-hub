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

Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes...
Route::get('login', 'Cms\Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Cms\Auth\LoginController@login');
Route::get('logout', 'Cms\Auth\LoginController@logout')->name('logout');

// Password Reset Routes...
Route::get('password/reset', 'Cms\Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Cms\Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Cms\Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Cms\Auth\ResetPasswordController@reset');

Route::get('dashboard', 'Cms\DashboardController@getIndex')->middleware('auth')->name('dashboard');

// Dashboard Routes
Route::get('catalogue-manager/products', 'Cms\CatalogueManager\ProductsController@getIndex')->middleware('auth')->name('cm_products');
Route::get('catalogue-manager/products/{id}', 'Cms\CatalogueManager\ProductsController@getEdit')->middleware('auth')->name('cm_products_edit');

Route::get('catalogue-manager/collections', 'Cms\CatalogueManager\CollectionsController@getIndex')->middleware('auth')->name('cm_collections');
Route::get('catalogue-manager/collections/{id}', 'Cms\CatalogueManager\CollectionsController@getEdit')->middleware('auth')->name('cm_collections_edit');

Route::get('catalogue-manager/categories', 'Cms\CatalogueManager\CategoriesController@getIndex')->middleware('auth')->name('cm_categories');
Route::get('catalogue-manager/category/{id}', 'Cms\CatalogueManager\CategoriesController@getEdit')->middleware('auth')->name('cm_category');