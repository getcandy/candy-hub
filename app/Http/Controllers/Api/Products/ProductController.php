<?php

namespace GetCandy\Http\Controllers\Api\Products;

use GetCandy\Exceptions\InvalidLanguageException;
use GetCandy\Exceptions\MinimumRecordRequiredException;
use GetCandy\Http\Controllers\Api\BaseController;
use GetCandy\Http\Requests\Api\Assets\UploadRequest;
use GetCandy\Http\Requests\Api\Products\CreateRequest;
use GetCandy\Http\Requests\Api\Products\CreateUrlRequest;
use GetCandy\Http\Requests\Api\Products\DeleteRequest;
use GetCandy\Http\Requests\Api\Products\UpdateAttributesRequest;
use GetCandy\Http\Requests\Api\Products\UpdateCollectionsRequest;
use GetCandy\Http\Requests\Api\Products\CreateVariantsRequest;
use GetCandy\Http\Requests\Api\Products\UpdateRequest;
use GetCandy\Http\Transformers\Fractal\Assets\AssetTransformer;
use GetCandy\Http\Transformers\Fractal\Products\ProductTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductController extends BaseController
{
    /**
     * Handles the request to show all products
     * @param  Request $request
     * @return array
     */
    public function index(Request $request)
    {
        $paginator = app('api')->products()->getPaginatedData($request->keywords, $request->per_page, $request->current_page);
        return $this->respondWithCollection($paginator, new ProductTransformer);
    }

    /**
     * Handles the request to show a product based on hashed ID
     * @param  String $id
     * @return array|\Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $product = app('api')->products()->getByHashedId($id);
        } catch (ModelNotFoundException $e) {
            return $this->errorNotFound();
        }
        return $this->respondWithItem($product, new ProductTransformer);
    }

    /**
     * Handles the request to create a new product
     * @param  CreateRequest $request
     * @return array
     */
    public function store(CreateRequest $request)
    {
        try {
            $result = app('api')->products()->create($request->all());
        } catch (InvalidLanguageException $e) {
            return $this->errorUnprocessable($e->getMessage());
        }
        return $this->respondWithItem($result, new ProductTransformer);
    }

    /**
     * Handles the request to update a product
     * @param  String        $id
     * @param  UpdateRequest $request
     * @return array|\Illuminate\Http\Response
     */
    public function update($id, UpdateRequest $request)
    {
        try {
            $result = app('api')->products()->update($id, $request->all());
        } catch (MinimumRecordRequiredException $e) {
            return $this->errorUnprocessable($e->getMessage());
        } catch (NotFoundHttpException $e) {
            return $this->errorNotFound();
        } catch (HttpException $e) {
            return $this->errorUnprocessable($e->getMessage());
        } catch (InvalidLanguageException $e) {
            return $this->errorUnprocessable($e->getMessage());
        }
        return $this->respondWithItem($result, new ProductTransformer);
    }

    /**
     * Handles the request to update a products attributes
     * @param  String        $product
     * @param  UpdateAttributesRequest $request
     * @return Mixed
     */
    public function updateAttributes($product, UpdateAttributesRequest $request)
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


    /**
     * @param $product
     * @param UpdateCollectionsRequest $request
     * @return array|\Illuminate\Http\Response
     */
    public function updateCollections($product, UpdateCollectionsRequest $request)
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

    /**
     * @param                                                       $product
     * @param \GetCandy\Http\Requests\Api\Products\CreateUrlRequest $request
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function createUrl($product, CreateUrlRequest $request)
    {
        $result = app('api')->products()->createUrl($product, $request->all());
        return $this->respondWithNoContent();
    }

    public function uploadAsset($id, UploadRequest $request)
    {
        $product = app('api')->products()->getByHashedId($id);
        $asset = app('api')->assets()->upload(
            $request->all(),
            $product,
            $product->assets()->count() + 1
        );
        return $this->respondWithItem($asset, new AssetTransformer);
    }

    public function getAssets($id, Request $request)
    {
        $product = app('api')->products()->getByHashedId($id);
        $assets = app('api')->assets()->getAssets($product, $request->all());

        return $this->respondWithCollection($assets, new AssetTransformer);
    }

    /**
     * @param                                                       $product
     * @param \GetCandy\Http\Requests\Api\Products\CreateUrlRequest $request
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function createRedirect($product, CreateUrlRequest $request)
    {
        $result = app('api')->products()->createUrl($product, $request->all());
        return $this->respondWithNoContent();
    }

    public function removeCategory($productID, $categoryID, DeleteRequest $request)
    {
        $result = app('api')->products()->removeCategory($productID, $categoryID);

        if ($result) {
            return response()->json([
                'message' => 'Successfully removed category from product',
                'categoryName' => 'test'
            ], 202);
        }
        return response()->json('Error', 500);
    }

    /**
     * Handles the request to delete a product
     * @param  String        $id
     * @param  DeleteRequest $request
     * @return Json
     */
    public function destroy($product, DeleteRequest $request)
    {
        try {
            $result = app('api')->products()->delete($product);
        } catch (MinimumRecordRequiredException $e) {
            return $this->errorUnprocessable($e->getMessage());
        } catch (NotFoundHttpException $e) {
            return $this->errorNotFound();
        } catch (ModelNotFoundException $e) {
            return $this->errorNotFound();
        }
        return $this->respondWithNoContent();
    }
}
