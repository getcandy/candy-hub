<?php

namespace GetCandy\Cms\Http\Controllers\CatalogueManager;

use GetCandy\Cms\Http\Controllers\Controller;

class ProductsController extends Controller
{
    public function getIndex()
    {
        return view('getcandy_cms::catalogue-manager.products.index');
    }

    public function getEdit()
    {
        return view('getcandy_cms::catalogue-manager.products.edit');
    }
}
