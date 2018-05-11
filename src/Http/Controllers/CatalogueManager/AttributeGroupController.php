<?php
namespace GetCandy\Hub\Http\Controllers\CatalogueManager;

use GetCandy\Hub\Http\Controllers\Controller;

class AttributeGroupController extends Controller
{
    public function getIndex()
    {
        return view('hub::catalogue-manager.attribute-groups.index');
    }

    public function getShow($id)
    {
        return view('hub::catalogue-manager.attribute-groups.edit', [
            'id' => $id
        ]);
    }
}
