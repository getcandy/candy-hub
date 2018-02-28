<?php
namespace GetCandy\Hub\Http\Controllers\MarketingSuite;

use GetCandy\Hub\Http\Controllers\Controller;

class DiscountController extends Controller
{
    public function getIndex()
    {
        return view('hub::marketing-suite.discounts.index');
    }

    public function getCreate()
    {
        return view('hub::marketing-suite.discounts.create');
    }

    public function getEdit($id)
    {
        return view('hub::marketing-suite.discounts.edit', [
            'id' => $id
        ]);
    }
}
