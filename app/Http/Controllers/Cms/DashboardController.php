<?php

namespace GetCandy\Cms\Http\Controllers;

class DashboardController extends Controller
{
    public function getIndex()
    {
        return view('getcandy_cms::dashboard');
    }
}
