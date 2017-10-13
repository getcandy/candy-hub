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
$this->get('products/{id}', 'Products\ProductController@show');
$this->get('products', 'Products\ProductController@index');

/**
 * Categories
 */
$this->get('categories', 'Categories\CategoryController@index');

$this->get('routes', 'Routes\RouteController@index');
$this->get('routes/{slug}', [
    'uses' => 'Routes\RouteController@show'
])->where(['slug' => '.*']);
