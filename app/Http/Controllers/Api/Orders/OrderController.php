<?php
namespace GetCandy\Http\Controllers\Api\Orders;

use GetCandy\Http\Controllers\Api\BaseController;
use GetCandy\Http\Requests\Api\Orders\CreateRequest;
use GetCandy\Http\Requests\Api\Orders\ProcessRequest;
use GetCandy\Http\Requests\Api\Orders\UpdateRequest;
use GetCandy\Http\Requests\Api\Orders\StoreAddressRequest;
use GetCandy\Http\Transformers\Fractal\Orders\OrderTransformer;
use GetCandy\Http\Transformers\Fractal\Payments\TransactionTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OrderController extends BaseController
{

    /**
     * Returns a listing of channels
     * @return Json
     */
    public function index(Request $request)
    {
        $orders = app('api')->orders()->getPaginatedData($request->per_page, $request->page, $request->user());
        return $this->respondWithCollection($orders, new OrderTransformer);
    }

    /**
     * Handles the request to show a channel based on it's hashed ID
     * @param  String $id
     * @return Json
     */
    public function show($id)
    {
        try {
            $order = app('api')->orders()->getByHashedId($id);
        } catch (ModelNotFoundException $e) {
            return $this->errorNotFound();
        }

        return $this->respondWithItem($order, new OrderTransformer);
    }

    /**
     * Store either a new or existing basket
     *
     * @param CreateRequest $request
     * 
     * @return void
     */
    public function store(CreateRequest $request)
    {
        $order = app('api')->orders()->store($request->basket_id);
        return $this->respondWithItem($order, new OrderTransformer);
    }

    /**
     * Process an order
     *
     * @param ProcessRequest $request
     *
     * @return json
     */
    public function process(ProcessRequest $request)
    {
        $transaction = app('api')->orders()->process($request->all());
        switch ($transaction->status) {
            case 'processor_declined':
                return $this->errorForbidden('Payment was declined');
            default:
                return $this->respondWithItem($transaction, new TransactionTransformer);
        }
    }

    /**
     * Expire an order
     *
     * @param ExpireRequest $request
     * 
     * @return json
     */
    public function expire($id)
    {
        try {
            $result = app('api')->orders()->expire($id);
        } catch (ModelNotFoundException $e) {
            return $this->errorNotFound();
        }
        return $this->respondWithNoContent();
    }

    public function shippingAddress($id, StoreAddressRequest $request)
    {
        try {
            $order = app('api')->orders()->setShipping($id, $request->all());
        } catch (ModelNotFoundException $e) {
            return $this->errorNotFound();
        }
        return $this->respondWithItem($order, new OrderTransformer);
    }

    public function shippingOption(Request $request)
    {
        // 
    }

    public function billingAddress($id, StoreAddressRequest $request)
    {
        try {
            $order = app('api')->orders()->setBilling($id, $request->all());
        } catch (ModelNotFoundException $e) {
            return $this->errorNotFound();
        }
        return $this->respondWithItem($order, new OrderTransformer);
    }
}
