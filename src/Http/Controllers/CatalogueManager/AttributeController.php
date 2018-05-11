<?php
namespace GetCandy\Hub\Http\Controllers\CatalogueManager;

use GetCandy\Hub\Http\Controllers\Controller;

class AttributeController extends Controller
{
    public function getIndex()
    {
        return view('hub::catalogue-manager.attributes.index');
    }

    public function getShow($id)
    {
        return view('hub::catalogue-manager.attributes.edit', [
            'id' => $id
        ]);
    }
}
