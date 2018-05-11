<?php
namespace GetCandy\Hub\Http\Controllers\CatalogueManager;

use GetCandy\Hub\Http\Controllers\Controller;

class ProductFamilyController extends Controller
{
    public function getIndex()
    {
        return view('hub::catalogue-manager.product-families.index');
    }

    public function getEdit($id)
    {
        return view('hub::catalogue-manager.product-families.edit', [
            'id' => $id
        ]);
    }
}
