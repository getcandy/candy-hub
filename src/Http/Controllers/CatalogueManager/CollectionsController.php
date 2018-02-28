<?php

namespace GetCandy\Hub\Http\Controllers\CatalogueManager;

use GetCandy\Hub\Http\Controllers\Controller;

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
