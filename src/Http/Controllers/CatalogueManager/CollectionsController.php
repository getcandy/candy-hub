<?php

namespace GetCandy\Hub\Http\Controllers\CatalogueManager;

use GetCandy\Http\Controllers\Cms\Controller;

class CollectionsController extends Controller
{
    public function getIndex()
    {

        return view('hub::catalogue-manager.collections.index');
    }

    public function getEdit($id)
    {
        return view('hub::catalogue-manager.collections.edit', [
            'id' => $id
        ]);
    }
}
