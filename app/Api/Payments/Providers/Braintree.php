<?php

namespace GetCandy\Api\Payments\Providers;

use Braintree_Configuration;
use Braintree_ClientToken;
use Braintree_PaymentMethodNonce;
use Braintree_Exception_NotFound;
use Braintree_Transaction;
use Braintree_Test_Transaction;

class Braintree extends AbstractProvider
{
    public function __construct()
    {
        Braintree_Configuration::environment(config('getcandy.payments.environment'));
        Braintree_Configuration::merchantId(config('services.braintree.merchants.default'));
        Braintree_Configuration::publicKey(config('services.braintree.key'));
        Braintree_Configuration::privateKey(config('services.braintree.secret'));
    }

    public function getName()
    {
        return 'Braintree';
    }

    public function getClientToken()
    {
        return Braintree_ClientToken::generate();
    }

    public function threeDSecured()
    {
        return config('services.braintree.3D_secure');
    }

    //TODO: REMOVE BEFORE LIVE
    private function settle($sale)
    {
        if (!app()->isLocal()) {
            return $sale;
        }
        return Braintree_Test_Transaction::settle($sale->transaction->id);
    }

    public function validateToken($token)
    {
        try {
            $token = Braintree_PaymentMethodNonce::find($token);
            $info = $token->threeDSecureInfo;
            if ($token->consumed || (empty($info) && $this->threeDSecured())) {
                return false;
            }
        } catch (Braintree_Exception_NotFound $e) {
            return false;
        }
        return true;
    }

    public function charge($token, $amount, $options = [])
    {
        $sale = Braintree_Transaction::sale([
            'amount' => $amount,
            'paymentMethodNonce' => $token,
            'options' => [
                'submitForSettlement' => true
            ]
        ]);
        
        //TODO: REMOVE BEFORE LIVE
        $this->settle($sale);

        return $sale;
    }

    public function refund($token, $amount = null)
    {
        $transaction = Braintree_Transaction::refund($token, $amount);
        return $transaction;
    }
}
