<?php

namespace GetCandy\Http\Controllers\Api\Products;

use GetCandy\Http\Controllers\Api\BaseController;
use GetCandy\Http\Requests\Api\Products\UpdateCollectionsRequest;
use GetCandy\Http\Transformers\Fractal\Products\ProductTransformer;
use GetCandy\Http\Transformers\Fractal\Collections\CollectionTransformer;
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
            $collections = app('api')->productCollections()->update($product, $request->all());
        } catch (HttpException $e) {
            return $this->errorUnprocessable($e->getMessage());
        } catch (NotFoundHttpException $e) {
            return $this->errorNotFound();
        }
        return $this->respondWithCollection($collections, new CollectionTransformer);
    }

    /**
     * Deletes a products collection
     * @param  int        $productId
     * @param  int        $collectionId
     * @return array|\Illuminate\Http\Response
     */
    public function destroy($productId, $collectionId)
    {
        $result = app('api')->productCollections()->delete($productId, $collectionId);

        if ($result) {
            return response()->json([
                'message' => 'Successfully removed collection from product'
            ], 202);
        }
        return response()->json('Error', 500);
    }
}
