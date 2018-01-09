<?php

namespace GetCandy\Api\Pricing;

use TaxCalculator;
use CurrencyConverter;

class PriceCalculator
{
    public function get($price, $tax = 0)
    {
        $converted = CurrencyConverter::convert($price + $tax);
        return $converted;
    }
}
