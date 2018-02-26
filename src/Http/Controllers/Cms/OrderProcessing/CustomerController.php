<?php
namespace GetCandy\Http\Controllers\Cms\OrderProcessing;

use GetCandy\Http\Controllers\Cms\Controller;

class CustomerController extends Controller
{
    public function getIndex()
    {
        return view('order-processing.customers.index');
    }

    public function getShow($id)
    {
        return view('order-processing.customers.view', [
            'id' => $id
        ]);
    }
}
