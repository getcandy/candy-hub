<?php

namespace GetCandy\Api\Taxes;

use Illuminate\Database\Eloquent\ModelNotFoundException;

class TaxCalculator
{
    protected $rate;

    protected $taxable = false;

    public function setTax($type)
    {
        $this->set($type);
        $this->taxable = true;
        return $this;
    }

    public function setDefault()
    {
        $this->rate = app('api')->taxes()->getDefaultRecord();
        return $this;
    }
    public function get()
    {
        return $this->rate;
    }

    public function set($rate)
    {
        try {
            $this->rate = app('api')->taxes()->getByName($rate);
        } catch (ModelNotFoundException $e) {
            $this->setDefault();
        }
        return $this;
    }

    public function amount($price)
    {
        if (!$this->taxable) {
            return 0;
        }
        if (!$this->rate) {
            $this->setDefault();
        }
        return $this->amountToAdd($price);
    }

    protected function amountToAdd($price)
    {
        $exVat = $price * (($this->rate->percentage + 100) / 100);
        $amount =  $exVat - $price;
        return round($amount, 2);
    }

    public function deduct($price)
    {
        if (!$this->taxable) {
            return $price;
        }
        return round($price + $this->amountToAdd($price), 2);
    }
}
