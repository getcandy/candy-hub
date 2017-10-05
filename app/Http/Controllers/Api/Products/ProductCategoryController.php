<?php

namespace GetCandy\Http\Controllers\Api\Products;

use GetCandy\Http\Controllers\Api\BaseController;
use GetCandy\Http\Requests\Api\Products\UpdateCategoriesRequest;
use GetCandy\Http\Transformers\Fractal\Categories\CategoryTransformer;
use Illuminate\Http\Request;

class ProductCategoryController extends BaseController
{

    /**
     * Handles the request to update a products categories
     * @param  String        $id
     * @param  DeleteRequest $request
     * @return Json
     */
    public function update($product, UpdateCategoriesRequest $request)
    {
        $categories = app('api')->productCategories()->update($product, $request->all());
        return $this->respondWithCollection($categories, new CategoryTransformer);
    }


    /**
     * Deletes a products category
     * @param  int        $productId
     * @param  int        $categoryId
     * @param  DeleteRequest $request
     * @return array|\Illuminate\Http\Response
     */
    public function destroy($productId, $categoryId, DeleteRequest $request)
    {
        $result = app('api')->products()->removeCategory($product, $category);

        if ($result) {
            return response()->json([
                'message' => 'Successfully removed category from product',
                'categoryName' => 'test'
            ], 202);
        }
        return response()->json('Error', 500);
    }
}
