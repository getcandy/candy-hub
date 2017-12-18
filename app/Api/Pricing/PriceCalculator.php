<?php

namespace GetCandy\Api\Pricing;

use TaxCalculator;
use CurrencyConverter;

class PriceCalculator
{
    public function get($amount)
    {
        $converted = CurrencyConverter::convert($amount);
        $untaxed = TaxCalculator::deduct($converted);
        return $untaxed;
    }
}
