<?php

namespace GetCandy\Hub\Http\Controllers\OrderProcessing;

use GetCandy\Hub\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GetCandy\Api\Core\Orders\Interfaces\OrderCriteriaInterface;

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

    public function getExport($format, Request $request, OrderCriteriaInterface $orders)
    {
        $config = config('getcandy.orders.exports.'.$request->format, config('getcandy.orders.exports.csv'));

        if (!view()->exists($config['view'] ?? null)) {
            return $this->errorWrongArgs("View for \"{$request->format}\" not found.");
        }

        $orders = $orders->restrict(false)->set('ids', explode(':', $request->orders))->get();
        $content = view($config['view'], ['orders' => $orders])->render();

        return response()->streamDownload(function () use ($content) {
            echo $content;
        }, 'order-export.csv');
    }

    public function invoice($id)
    {
        $order = app('api')->orders()->getByHashedId($id);
        $pdf = app('api')->orders()->getPdf($order);

        return $pdf->stream('#INV-'.$order->reference.'.pdf');
    }
}
