<?php
namespace GetCandy\Http\Controllers\Cms\CatalogueManager;

use GetCandy\Http\Controllers\Cms\Controller;

class DiscountController extends Controller
{
    public function getIndex()
    {
        return view('catalogue-manager.discounts.index');
    }

    public function getCreate()
    {
        return view('catalogue-manager.discounts.create');
    }

    public function getEdit($id)
    {
        return view('catalogue-manager.discounts.edit', [
            'id' => $id
        ]);
    }
}
