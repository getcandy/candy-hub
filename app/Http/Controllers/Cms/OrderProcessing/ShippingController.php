<?php
namespace GetCandy\Http\Controllers\Cms\OrderProcessing;

use GetCandy\Http\Controllers\Cms\Controller;

class ShippingController extends Controller
{
    public function getIndex()
    {
        return view('order-processing.shipping.methods.index');
    }

    public function getEdit($id)
    {
        return view('order-processing.shipping.methods.edit', [
            'id' => $id
        ]);
    }

    public function getZones()
    {
        return view('order-processing.shipping.zones.index');
    }

    public function getZone($id)
    {
        return view('order-processing.shipping.zones.edit', [
            'id' => $id
        ]);
    }
}
