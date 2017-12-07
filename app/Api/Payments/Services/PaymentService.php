<?php
namespace GetCandy\Api\Payments\Services;

use GetCandy\Api\Payments\Exceptions\AlreadyRefundedException;
use GetCandy\Api\Payments\Models\Transaction;
use GetCandy\Api\Scaffold\BaseService;

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

    public function charge($token, $amount)
    {
        $result = $this->getProvider()->charge($token, $amount);
        $transaction = new Transaction;
        $transaction->success = $result->success;
        $transaction->status = $result->transaction->status;
        $transaction->transaction_id = $result->transaction->id;
        $transaction->amount = $result->transaction->amount;
        $transaction->merchant = $this->getProvider()->getName();
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
        $refund = new Transaction;
        $refund->success = $result->success;
        $refund->status = $result->transaction->type;
        $refund->amount = -abs($result->transaction->amount);
        $refund->transaction_id = $result->transaction->id;
        $refund->merchant = $this->getProvider()->getName();
        $refund->order_id = $order->id;

        $order->status = 'refunded';
        $order->save();

        $refund->save();
        return $transaction;
    }
}
