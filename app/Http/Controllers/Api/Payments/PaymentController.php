<?php
namespace GetCandy\Http\Controllers\Api\Payments;

use GetCandy\Http\Controllers\Api\BaseController;
use GetCandy\Http\Transformers\Fractal\Payments\ProviderTransformer;
use Illuminate\Http\Request;
use GetCandy\Http\Requests\Api\Payments\RefundRequest;
use GetCandy\Api\Payments\Exceptions\AlreadyRefundedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PaymentController extends BaseController
{
    public function provider()
    {
        $provider = app('api')->payments()->getProvider();
        return $this->respondWithItem($provider, new ProviderTransformer);
    }

    public function refund($id, RefundRequest $request)
    {
        try {
            $result = app('api')->payments()->refund($id);
        } catch (AlreadyRefundedException $e) {
            return $this->errorWrongArgs('Refund already issued');
        } catch (ModelNotFoundException $e) {
            return $this->errorNotFound();
        }

        if (!$result->success) {
            return $this->errorWrongArgs($result->notes);
        }

        return $this->respondWithSuccess('Refund successful');
    }
}
