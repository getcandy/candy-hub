<?php

namespace GetCandy\Http\Controllers\Cms\CatalogueManager;

use GetCandy\Http\Controllers\Cms\Controller;

class CollectionsController extends Controller
{
    public function getIndex()
    {
        
        return view('catalogue-manager.collections.index');
    }

    public function getEdit($id)
    {
        return view('catalogue-manager.collections.edit', [
            'id' => $id
        ]);
    }
}
