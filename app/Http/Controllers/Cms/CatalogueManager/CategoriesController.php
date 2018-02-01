<?php

namespace GetCandy\Http\Controllers\Cms\CatalogueManager;

use GetCandy\Http\Controllers\Cms\Controller;

class CategoriesController extends Controller
{
    public function getIndex()
    {
        return view('catalogue-manager.categories.index');
    }

    public function getEdit($id)
    {
        return view('catalogue-manager.categories.edit', [
            'id' => $id
        ]);
    }
}
