<?php
namespace GetCandy\Http\Controllers\Cms\OrderProcessing;

use GetCandy\Http\Controllers\Cms\Controller;

class ShippingController extends Controller
{
    public function getIndex()
    {
        return view('order-processing.shipping.index');
    }

    public function getEdit($id)
    {
        return view('order-processing.shipping.edit', [
            'id' => $id
        ]);
    }
}
