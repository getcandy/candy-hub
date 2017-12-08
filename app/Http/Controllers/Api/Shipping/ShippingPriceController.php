<?php
namespace GetCandy\Http\Controllers\Api\Shipping;

use GetCandy\Http\Controllers\Api\BaseController;
use GetCandy\Http\Requests\Api\Shipping\Pricing\CreateRequest;
use GetCandy\Http\Transformers\Fractal\Shipping\ShippingPriceTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ShippingPriceController extends BaseController
{
    /**
     * Returns a listing of channels
     * @return Json
     */
    public function index(Request $request)
    {
        $orders = app('api')->shippingPrices()->getPaginatedData($request->per_page, $request->page);
        return $this->respondWithCollection($orders, new ShippingPriceTransformer);
    }

    /**
     * Handles the request to show a channel based on it's hashed ID
     * @param  String $id
     * @return Json
     */
    public function show($id)
    {
        try {
            $channel = app('api')->shippingPrices()->getByHashedId($id);
        } catch (ModelNotFoundException $e) {
            return $this->errorNotFound();
        }
        return $this->respondWithItem($channel, new ShippingPriceTransformer);
    }

    /**
     * Handles the request to create a new channel
     * @param  CreateRequest $request
     * @return Json
     */
    public function store($id, CreateRequest $request)
    {
        $result = app('api')->shippingPrices()->create($id, $request->all());
        return $this->respondWithItem($result, new ShippingPriceTransformer);
    }

    public function update($id, UpdateRequest $request)
    {
        try {
            $result = app('api')->shippingPrices()->update($id, $request->all());
        } catch (NotFoundHttpException $e) {
            return $this->errorNotFound();
        }
        return $this->respondWithItem($result, new ShippingPriceTransformer);
    }
}
