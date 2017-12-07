<?php
namespace GetCandy\Http\Transformers\Fractal\Payments;

use GetCandy\Http\Transformers\Fractal\BaseTransformer;
use GetCandy\Http\Transformers\Fractal\Orders\OrderTransformer;
use GetCandy\Api\Payments\Providers\AbstractProvider;
use GetCandy\Api\Payments\Models\Transaction;

class TransactionTransformer extends BaseTransformer
{
    protected $availableIncludes = [
        'order'
    ];

    public function transform(Transaction $transaction)
    {
        return [
            'id' => $transaction->encodedId(),
            'transaction_id' => $transaction->transaction_id,
            'provider' => $transaction->merchant,
            'amount' => $transaction->amount,
            'success' => (bool) $transaction->success,
            'status' => $transaction->status
        ];
    }

    public function includeOrder(Transaction $transaction)
    {
        return $this->item($transaction->order, new OrderTransformer);
    }
}
