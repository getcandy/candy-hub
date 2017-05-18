<?php

Route::get('reviews', '\GetCandy\Plugins\ReviewsRatings\Http\Controllers\Cms\ReviewsController@getIndex')->name('reviews');
