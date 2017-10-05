<?php

namespace GetCandy\Http\Controllers\Api\Products;

use GetCandy\Http\Controllers\Api\BaseController;
use GetCandy\Http\Requests\Api\Products\UpdateCollectionsRequest;
use GetCandy\Http\Transformers\Fractal\Products\ProductTransformer;
use Illuminate\Http\Request;

class ProductCollectionController extends BaseController
{

    /**
     * @param $product
     * @param UpdateCollectionsRequest $request
     * @return array|\Illuminate\Http\Response
     */
    public function update($product, UpdateCollectionsRequest $request)
    {
        try {
            $result = app('api')->products()->updateCollections($product, $request->all());
        } catch (HttpException $e) {
            return $this->errorUnprocessable($e->getMessage());
        } catch (NotFoundHttpException $e) {
            return $this->errorNotFound();
        }
        return $this->respondWithItem($result, new ProductTransformer);
    }
}
