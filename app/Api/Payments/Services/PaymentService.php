<?php
namespace GetCandy\Api\Payments\Services;

use GetCandy\Api\Orders\Models\Order;
use GetCandy\Api\Scaffold\BaseService;
use GetCandy\Api\Payments\Models\Transaction;
use GetCandy\Api\Payments\Exceptions\AlreadyRefundedException;

class PaymentService extends BaseService
{
    protected $configPath = 'getcandy.payments';

    public function __construct()
    {
        $this->model = new Transaction;
    }
    
    /**
     * Gets the payment provider class
     *
     * @return void
     */
    public function getProvider()
    {
        $provider = config(
            $this->configPath . '.providers.' . config($this->configPath . '.gateway', 'braintree')
        );
        return app()->make($provider);
    }
    
    /**
     * Validates a payment token
     *
     * @param string $token
     * 
     * @return void
     */
    public function validateToken($token)
    {
        return $this->getProvider()->validateToken($token);
    }

    public function charge($token, Order $order)
    {
        $result = $this->getProvider()->charge($token, $order);
        $transaction = new Transaction;
        $transaction->success = $result->success;
        $transaction->status = $result->transaction->status;
        $transaction->transaction_id = $result->transaction->id;
        $transaction->amount = $result->transaction->amount;
        $transaction->merchant = $result->transaction->merchantAccountId;
        $transaction->card_type = $result->transaction->creditCardDetails->cardType;
        $transaction->last_four = $result->transaction->creditCardDetails->last4;
        return $transaction;
    }

    /**
     * Refund a sale
     *
     * @param string $token
     * 
     * @return void
     */
    public function refund($token)
    {
        $transaction = $this->getByHashedId($token);
        $order = $transaction->order;

        if ($order->status == 'refunded') {
            throw new AlreadyRefundedException();
        }

        $result = $this->getProvider()->refund($transaction->transaction_id, $transaction->amount);

        if ($result->success) {
            $data = [
                'transaction_id' => $result->transaction->id,
                'success' => true,
                'amount' => -abs($result->transaction->amount),
                'status' => $result->transaction->type,
                'merchant' => '-',
                'order' => $order,
                'notes' => $result->message
            ];
        } else {
            $data = [
                'transaction_id' => $result->params['id'],
                'success' => false,
                'amount' => $result->params['transaction']['amount'],
                'status' => 'error',
                'merchant' => $result->params['merchantId'],
                'order' => $order,
                'notes' => ''
            ];
        }

        $refund = $this->createTransaction($data);
        
        // Add up each transaction that isn't voided or refunded and successful.
        
        // TODO Improve the way this is checked...
        if ($order->transactions()->charged()->count() === 1 && $order->total === $transaction->amount) {
            $order->status = 'refunded';
        }

        $transaction->status = 'refunded';
        $order->save();
        $transaction->save();

        return $refund;
    }

    /**
     * Creates a transaction
     *
     * @param array $data
     * 
     * @return Transaction
     */
    protected function createTransaction(array $data)
    {
        $transaction = new Transaction;
        $transaction->success = $data['success'];
        $transaction->status = $data['status'];
        $transaction->amount = $data['amount'];
        $transaction->transaction_id = $data['transaction_id'];
        $transaction->merchant = $data['merchant'];
        $transaction->order_id = $data['order']->id;
        $transaction->notes = $data['notes'];
        $transaction->save();
        return $transaction;
    }

    /**
     * Voids a transaction
     *
     * @param string $transactionId
     * 
     * @return Transaction
     */
    public function void($transactionId)
    {
        $transaction = $this->getByHashedId($transactionId);
        $order = $transaction->order;

        $result = $this->getProvider()->void($transaction->transaction_id);

        $transaction->success = $result->success;
        $transaction->status = 'voided';
        
        if (!$result->success) {
            $transaction->notes = $result->message;
        }

        // TODO Improve the way this is checked...
        if ($order->transactions()->charged()->count() === 1 && $order->total === $transaction->amount) {
            $order->status = 'voided';
            $order->save();
        }

        $transaction->save();
        return $transaction;
    }
}
