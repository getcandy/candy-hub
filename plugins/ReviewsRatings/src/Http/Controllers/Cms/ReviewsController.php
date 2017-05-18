<?php

namespace GetCandy\Plugins\ReviewsRatings\Http\Controllers\Cms;

use GetCandy\Http\Controllers\Cms\Controller;

class ReviewsController extends Controller
{
    public function getIndex()
    {
        return view('reviewsratings::index');
    }
}
