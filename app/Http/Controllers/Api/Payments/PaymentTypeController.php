<?php
namespace GetCandy\Http\Controllers\Api\Payments;

use Illuminate\Http\Request;
use GetCandy\Http\Controllers\Api\BaseController;
use GetCandy\Api\Payments\Services\PaymentTypeService;
use GetCandy\Http\Transformers\Fractal\Payments\PaymentTypeTransformer;

class PaymentTypeController extends BaseController
{
    public function index()
    {
        $types = app('api')->paymentTypes()->all();
        return $this->respondWithCollection($types, new PaymentTypeTransformer);
    }
}
