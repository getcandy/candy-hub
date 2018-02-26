<?php

namespace GetCandy\Http\Controllers\Api\Products;

use GetCandy\Http\Controllers\Api\BaseController;
use GetCandy\Http\Requests\Api\Assets\UploadRequest;
use GetCandy\Http\Transformers\Fractal\Assets\AssetTransformer;
use Illuminate\Http\Request;

class ProductAssetController extends BaseController
{
    /**
     * Gets all assets for a product
     * @param  int  $id
     * @param  Request $request
     * @return array|\Illuminate\Http\Response
     */
    public function index($id, Request $request)
    {
        $product = app('api')->products()->getByHashedId($id);
        $assets = app('api')->assets()->getAssets($product, $request->all());

        return $this->respondWithCollection($assets, new AssetTransformer);
    }

    /**
     * Uploads an asset for a product
     * @param  int        $id
     * @param  UploadRequest $request
     * @return array|\Illuminate\Http\Response
     */
    public function upload($id, UploadRequest $request)
    {

    }
}
