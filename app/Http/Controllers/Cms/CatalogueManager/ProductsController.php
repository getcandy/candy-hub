<?php

namespace GetCandy\Http\Controllers\Cms\CatalogueManager;

use GetCandy\Http\Controllers\Cms\Controller;

class ProductsController extends Controller
{
    public function getIndex()
    {
        return view('catalogue-manager.products.index');
    }

    public function getEdit($id)
    {
        return view('catalogue-manager.products.edit', [
            'id' => $id
        ]);
    }
}
