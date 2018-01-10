<?php

namespace GetCandy\Api\Taxes;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use GetCandy\Api\Taxes\Models\Tax;

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
            if ($rate instanceof Tax) {
                $this->rate = $rate;
            } else {
                $this->rate = app('api')->taxes()->getByName($rate);
            }
        } catch (ModelNotFoundException $e) {
            $this->setDefault();
        }
        return $this;
    }

    public function amount($price)
    {
        if (!$this->rate) {
            return 0;
        }
        return $this->amountToAdd($price);
    }

    protected function amountToAdd($price)
    {
        if (!$this->taxable) {
            return 0;
        }
        $exVat = $price * (($this->rate->percentage + 100) / 100);
        $amount =  $exVat - $price;
        return number_format($amount, 2);
    }

    public function add($price)
    {
        if (!$this->taxable) {
            return $price;
        }
        return number_format($price + $this->amountToAdd($price), 2);
    }
}
