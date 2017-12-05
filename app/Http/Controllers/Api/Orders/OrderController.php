<?php
namespace GetCandy\Http\Controllers\Api\Baskets;

use GetCandy\Http\Controllers\Api\BaseController;
use GetCandy\Http\Requests\Api\Baskets\CreateRequest;
use GetCandy\Http\Requests\Api\Baskets\UpdateRequest;
use GetCandy\Http\Transformers\Fractal\Orders\OrderTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BasketController extends BaseController
{

    /**
     * Returns a listing of channels
     * @return Json
     */
    public function index(Request $request)
    {
        $orders = app('api')->orders()->getPaginatedData($request->per_page);
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
            $basket = app('api')->baskets()->getByHashedId($id);
        } catch (ModelNotFoundException $e) {
            return $this->errorNotFound();
        }
        return $this->respondWithItem($basket, new BasketTransformer);
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
}
