<?php

namespace GetCandy\Hub\Http\Controllers\CatalogueManager;

use GetCandy\Hub\Http\Controllers\Controller;

class CategoriesController extends Controller
{
    public function getIndex()
    {
        return view('hub::catalogue-manager.categories.index');
    }

    public function getEdit($id)
    {
        return view('hub::catalogue-manager.categories.edit', [
            'id' => $id,
        ]);
    }
}
