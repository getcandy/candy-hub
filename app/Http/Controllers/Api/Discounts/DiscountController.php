<?php

namespace GetCandy\Http\Controllers\Api\Discounts;

use GetCandy\Http\Controllers\Api\BaseController;
use GetCandy\Http\Requests\Api\Discounts\CreateRequest;
use GetCandy\Http\Transformers\Fractal\Discounts\DiscountTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class DiscountController extends BaseController
{

    public function index(Request $request)
    {
        $paginator = app('api')->discounts()->getPaginatedData(
            $request->per_page,
            $request->current_page
        );
        return $this->respondWithCollection($paginator, new DiscountTransformer);
    }

    public function store(CreateRequest $request)
    {
        app('api')->discounts()->create($request->all());
    }

    public function update(UpdateRequest $request)
    {
        app('api')->discounts()->update($request->all());
    }
    /**
     * Shows the discount resource
     *
     * @param string $id
     * 
     * @return void
     */
    public function show($id)
    {
        try {
            $discount = app('api')->discounts()->getByHashedId($id);
        } catch (ModelNotFoundException $e) {
            return $this->errorNotFound();
        }
        return $this->respondWithItem($discount, new DiscountTransformer);
    }
}
