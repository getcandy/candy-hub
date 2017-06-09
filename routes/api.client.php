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

$this->get('channels', 'ChannelController@index');
$this->get('channels/{id}', 'ChannelController@show');
$this->get('products', 'ProductController@index');
$this->get('product-families', 'ProductFamilyController@index');
$this->get('routes', 'RouteController@index');
