<?php

namespace GetCandy\Api\Currencies;

use Illuminate\Database\Eloquent\ModelNotFoundException;

class CurrencyConverter
{
    protected $currency;

    public function setDefault()
    {
        $this->currency = app('api')->currencies()->getDefaultRecord();
        return $this;
    }
    public function get()
    {
        return $this->currency;
    }

    public function set($currency)
    {
        try {
            $this->currency = app('api')->currencies()->getByCode($currency);
        } catch (ModelNotFoundException $e) {
            $this->setDefault();
        }
        return $this;
    }

    public function convert($price, $currency = null)
    {
        return round($price * $this->currency->exchange_rate, 2);
    }
}
