<?php
namespace GetCandy\Http\Controllers\Api\Baskets;

use Illuminate\Http\Request;
use GetCandy\Http\Controllers\Api\BaseController;
use GetCandy\Http\Requests\Api\Baskets\CreateRequest;
use GetCandy\Http\Requests\Api\Baskets\UpdateRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use GetCandy\Http\Requests\Api\Baskets\AddDiscountRequest;
use GetCandy\Http\Transformers\Fractal\Baskets\BasketTransformer;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BasketController extends BaseController
{

    /**
     * Returns a listing of channels
     * @return Json
     */
    public function index(Request $request)
    {
        $attributes = app('api')->baskets()->getPaginatedData($request->per_page);
        return $this->respondWithCollection($attributes, new BasketTransformer);
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

    public function discount($basketId, AddDiscountRequest $request)
    {
        $basket = app('api')->baskets()->addDiscount($basketId, $request->coupon);
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
        $basket = app('api')->baskets()->store($request->all(), $request->user());
        return $this->respondWithItem($basket, new BasketTransformer);
    }

    /**
     * Gets the basket for the current user
     *
     * @param Request $request
     * @return void
     */
    public function current(Request $request)
    {
        $basket = app('api')->baskets()->getforUser($request->user());
        if (!$basket) {
            return $this->errorNotFound("Basket does't exist");
        }
        return $this->respondWithItem($basket, new BasketTransformer);
    }

    public function resolve(Request $request)
    {
        $basket = app('api')->baskets()->resolve($request->user(), $request->basket_id, $request->merge);
    }
}
