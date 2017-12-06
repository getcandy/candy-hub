<?php
namespace GetCandy\Http\Controllers\Api\Payments;

use GetCandy\Http\Controllers\Api\BaseController;
use GetCandy\Http\Transformers\Fractal\Payments\ProviderTransformer;
use Illuminate\Http\Request;

class PaymentController extends BaseController
{
    public function provider()
    {
        $provider = app('api')->payments()->getProvider();
        return $this->respondWithItem($provider, new ProviderTransformer);
    }

    public function refund($id)
    {
        $result = app('api')->payments()->refund($id);
        return $this->respondWithSuccess('Refund successful');
    }
}
