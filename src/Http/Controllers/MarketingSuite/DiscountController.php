<?php
namespace GetCandy\Http\Controllers\Cms\MarketingSuite;

use GetCandy\Http\Controllers\Cms\Controller;

class DiscountController extends Controller
{
    public function getIndex()
    {
        return view('marketing-suite.discounts.index');
    }

    public function getCreate()
    {
        return view('marketing-suite.discounts.create');
    }

    public function getEdit($id)
    {
        return view('marketing-suite.discounts.edit', [
            'id' => $id
        ]);
    }
}
