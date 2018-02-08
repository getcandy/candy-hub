<?php
namespace GetCandy\Http\Controllers\Api\Orders;

use GetCandy\Http\Controllers\Api\BaseController;
use GetCandy\Http\Requests\Api\Orders\CreateRequest;
use GetCandy\Http\Requests\Api\Orders\ProcessRequest;
use GetCandy\Http\Requests\Api\Orders\UpdateRequest;
use GetCandy\Http\Requests\Api\Orders\StoreAddressRequest;
use GetCandy\Http\Transformers\Fractal\Orders\OrderTransformer;
use GetCandy\Http\Transformers\Fractal\Shipping\ShippingPriceTransformer;
use GetCandy\Http\Transformers\Fractal\Payments\TransactionTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use GetCandy\Api\Orders\Exceptions\IncompleteOrderException;

class OrderController extends BaseController
{

    /**
     * Returns a listing of channels
     * @return Json
     */
    public function index(Request $request)
    {
        $orders = app('api')->orders()->getPaginatedData(
            $request->per_page,
            $request->current_page,
            $request->user(),
            $request->sort,
            $request->keywords
        );
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
        $order = app('api')->orders()->store($request->basket_id, $request->user());
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
        try {
            $transaction = app('api')->orders()->process($request->all());
            switch ($transaction->status) {
                case 'processor_declined':
                    return $this->errorForbidden('Payment was declined');
                default:
                    return $this->respondWithItem($transaction, new TransactionTransformer);
            }
        } catch (IncompleteOrderException $e) {
            return $this->errorForbidden('The order is missing billing information');
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

    /**
     * Set the shipping address of an order
     *
     * @param string $id
     * @param StoreAddressRequest $request
     *
     * @return array
     */
    public function shippingAddress($id, StoreAddressRequest $request)
    {
        try {
            $order = app('api')->orders()->setShipping($id, $request->all(), $request->user());
        } catch (ModelNotFoundException $e) {
            return $this->errorNotFound();
        }
        return $this->respondWithItem($order, new OrderTransformer);
    }

    /**
     * Update an order
     *
     * @param string $id
     * @param Request $request
     *
     * @return array
     */
    public function update($id, UpdateRequest $request)
    {
        try {
            $order = app('api')->orders()->update($id, $request->all());
        } catch (ModelNotFoundException $e) {
            return $this->errorNotFound();
        }
        return $this->respondWithItem($order, new OrderTransformer);
    }

    /**
     * Get shipping methods for an order
     *
     * @param string $orderId
     * @param Request $request
     *
     * @return array
     */
    public function shippingMethods($orderId, Request $request)
    {
        try {
            $options = app('api')->shippingMethods()->getForOrder($orderId);
        } catch (ModelNotFoundException $e) {
            return $this->errorNotFound();
        }

        return $this->respondWithCollection($options, new ShippingPriceTransformer);
    }

    /**
     * Add a contact to an order
     *
     * @param string $orderId
     * @param Request $request
     *
     * @return array
     */
    public function addContact($orderId, Request $request)
    {
        try {
            $order = app('api')->orders()->setContact($orderId, $request->all());
        } catch (ModelNotFoundException $e) {
            return $this->errorNotFound();
        }
        return $this->respondWithItem($order, new OrderTransformer);
    }

    /**
     * Set an orders billing address
     *
     * @param string $id
     * @param StoreAddressRequest $request
     *
     * @return array
     */
    public function billingAddress($id, StoreAddressRequest $request)
    {
        try {
            $order = app('api')->orders()->setBilling($id, $request->all());
        } catch (ModelNotFoundException $e) {
            return $this->errorNotFound();
        }
        return $this->respondWithItem($order, new OrderTransformer);
    }

    /**
     * Set shipping cost of an order
     *
     * @param string $id
     * @param Request $request
     *
     * @return array
     */
    public function shippingCost($id, Request $request)
    {
        try {
            $order = app('api')->orders()->setShippingCost($id, $request->price_id);
        } catch (ModelNotFoundException $e) {
            return $this->errorNotFound();
        }
        return $this->respondWithItem($order, new OrderTransformer);
    }

    public function invoice($id, Request $request)
    {
        $order = app('api')->orders()->getPdf($id);

        dd($order);
    }
}
