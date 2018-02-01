<?php
namespace GetCandy\Http\Controllers\Api\Payments;

use Illuminate\Http\Request;
use GetCandy\Http\Controllers\Api\BaseController;
use GetCandy\Http\Requests\Api\Payments\VoidRequest;
use GetCandy\Http\Requests\Api\Payments\RefundRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use GetCandy\Api\Payments\Exceptions\AlreadyRefundedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use GetCandy\Http\Transformers\Fractal\Payments\ProviderTransformer;
use GetCandy\Http\Transformers\Fractal\Payments\TransactionTransformer;

class PaymentController extends BaseController
{
    public function provider()
    {
        $provider = app('api')->payments()->getProvider();
        return $this->respondWithItem($provider, new ProviderTransformer);
    }

    /**
     * Handle the request to refund a transaction
     *
     * @param string $id
     * @param RefundRequest $request
     * 
     * @return mixed
     */
    public function refund($id, RefundRequest $request)
    {
        try {
            $transaction = app('api')->payments()->refund($id);
        } catch (AlreadyRefundedException $e) {
            return $this->errorWrongArgs('Refund already issued');
        } catch (ModelNotFoundException $e) {
            return $this->errorNotFound();
        }

        if (!$transaction->success) {
            return $this->errorWrongArgs($transaction->notes);
        }

        return $this->respondWithItem($transaction, new TransactionTransformer);
    }
    
    public function void($id, VoidRequest $request)
    {
        try {
            $transaction = app('api')->payments()->void($id);
        } catch (ModelNotFoundException $e) {
            return $this->errorNotFound();
        }

        if (!$transaction->success) {
            return $this->errorWrongArgs($transaction->notes);
        }

        return $this->respondWithItem($transaction, new TransactionTransformer);
    }
}
