<?php

namespace GetCandy\Http\Controllers\Api\Products;

use GetCandy\Http\Controllers\Api\BaseController;
use GetCandy\Http\Requests\Api\Products\CreateUrlRequest;
use GetCandy\Http\Requests\Api\Products\UpdateAttributesRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductAttributeController extends BaseController
{
    /**
     * Handles the request to update a products attributes
     * @param  String        $product
     * @param  UpdateAttributesRequest $request
     * @return Mixed
     */
    public function update($product, UpdateAttributesRequest $request)
    {
        try {
            $result = app('api')->products()->updateAttributes($product, $request->all());
        } catch (HttpException $e) {
            return $this->errorUnprocessable($e->getMessage());
        } catch (NotFoundHttpException $e) {
            return $this->errorNotFound();
        }
        return $this->respondWithItem($result, new ProductTransformer);
    }
}
