<?php

namespace GetCandy\Hub\Http\Controllers;

use Carbon\Carbon;

class DashboardController extends Controller
{
    public function getIndex()
    {
        return view('hub::dashboard');
    }
}
