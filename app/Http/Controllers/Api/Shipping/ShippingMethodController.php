<?php
namespace GetCandy\Http\Controllers\Api\Shipping;

use GetCandy\Http\Controllers\Api\BaseController;
use GetCandy\Http\Transformers\Fractal\Shipping\ShippingMethodTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use GetCandy\Http\Requests\Api\Shipping\CreateRequest;
use GetCandy\Http\Requests\Api\Shipping\UpdateRequest;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ShippingMethodController extends BaseController
{

    /**
     * Returns a listing of channels
     * @return Json
     */
    public function index(Request $request)
    {
        $orders = app('api')->shippingMethods()->getPaginatedData($request->per_page, $request->current_page);
        return $this->respondWithCollection($orders, new ShippingMethodTransformer);
    }

    /**
     * Handles the request to show a channel based on it's hashed ID
     * @param  String $id
     * @return Json
     */
    public function show($id)
    {
        try {
            $channel = app('api')->shippingMethods()->getByHashedId($id);
        } catch (ModelNotFoundException $e) {
            return $this->errorNotFound();
        }
        return $this->respondWithItem($channel, new ShippingMethodTransformer);
    }

    /**
     * Handles the request to create a new channel
     * @param  CreateRequest $request
     * @return Json
     */
    public function store(CreateRequest $request)
    {
        $result = app('api')->shippingMethods()->create($request->all());
        return $this->respondWithItem($result, new ShippingMethodTransformer);
    }

    public function update($id, UpdateRequest $request)
    {
        try {
            $result = app('api')->shippingMethods()->update($id, $request->all());
        } catch (NotFoundHttpException $e) {
            return $this->errorNotFound();
        }
        return $this->respondWithItem($result, new ShippingMethodTransformer);
    }

    public function updateZones($id, Request $request)
    {
        $method = app('api')->shippingMethods()->updateZones($id, $request->all());
        return $this->respondWithItem($method, new ShippingMethodTransformer);
    }
}
