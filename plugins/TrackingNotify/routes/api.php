<?php

Route::group(['prefix' => 'api/v1'], function () {
    Route::post('orders/{id}/sendtracking', 'GetCandy\Plugins\TrackingNotify\Http\Controllers\TrackingNotifyController@sendmailable');
});
