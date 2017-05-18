<?php

namespace GetCandy\Http\Controllers\Cms;

class DashboardController extends Controller
{
    public function getIndex()
    {
        return view('dashboard');
    }
}
