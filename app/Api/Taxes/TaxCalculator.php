<?php

namespace GetCandy\Api\Taxes;

use Illuminate\Database\Eloquent\ModelNotFoundException;

class TaxCalculator
{
    protected $rate;

    protected $deductable = false;

    public function setDeductable($type)
    {
        $this->set($type);
        $this->deductable = true;
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

    public function deduct($price)
    {
        if (!$this->deductable) {
            return $price;
        }
        return round($price * ((100 - $this->rate->percentage) / 100), 2);
    }
}
