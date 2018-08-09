<?php

namespace GetCandy\Hub\Http\Controllers\CatalogueManager;

use GetCandy\Hub\Http\Controllers\Controller;

class ProductsController extends Controller
{
    public function getIndex()
    {
        return view('hub::catalogue-manager.products.index');
    }

    public function getEdit($id)
    {
        return view('hub::catalogue-manager.products.edit', [
            'id' => $id,
        ]);
    }
}
