<?php
namespace GetCandy\Hub\Http\Controllers\OrderProcessing;

use GetCandy\Hub\Http\Controllers\Controller;

class CustomerController extends Controller
{
    public function getIndex()
    {
        return view('hub::order-processing.customers.index');
    }

    public function getShow($id)
    {
        return view('hub::order-processing.customers.view', [
            'id' => $id
        ]);
    }
}
