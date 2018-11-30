<?php

namespace GetCandy\Hub\Http\Controllers\OrderProcessing;

use GetCandy\Hub\Http\Controllers\Controller;

class ShippingController extends Controller
{
    public function getIndex()
    {
        return view('hub::order-processing.shipping.methods.index');
    }

    public function getEdit($id)
    {
        return view('hub::order-processing.shipping.methods.edit', [
            'id' => $id,
        ]);
    }

    public function getZones()
    {
        return view('hub::order-processing.shipping.zones.index');
    }

    public function getZone($id)
    {
        return view('hub::order-processing.shipping.zones.edit', [
            'id' => $id,
        ]);
    }
}
