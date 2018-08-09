<?php

namespace GetCandy\Hub\Http\Controllers\OrderProcessing;

use GetCandy\Hub\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function getIndex()
    {
        return view('hub::order-processing.orders.index');
    }

    public function getEdit($id)
    {
        return view('hub::order-processing.orders.edit', [
            'id' => $id,
        ]);
    }

    public function invoice($id)
    {
        $order = app('api')->orders()->getByHashedId($id);
        $pdf = app('api')->orders()->getPdf($order);

        return $pdf->stream('#INV-'.$order->reference.'.pdf');
    }
}
