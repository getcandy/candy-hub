<?php

Route::group(['prefix' => 'api/v1'], function () {
    Route::get('reviews', function () {
        return [
            'reviews' => 'yes'
        ];
    });
});
