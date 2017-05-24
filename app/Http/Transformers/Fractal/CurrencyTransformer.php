<?php

namespace GetCandy\Http\Transformers\Fractal;

use GetCandy\Api\Models\Currency;
use League\Fractal\TransformerAbstract;

class CurrencyTransformer extends TransformerAbstract
{
    protected $availableIncludes = [];

    public function transform(Currency $currency)
    {
        return [
            'id' => $currency->encodedId(),
            'name' => $currency->name,
            'code' => $currency->code,
            'decimal' => $currency->decimal_point,
            'thousand' => $currency->thousand_point,
            'exchange_rate' => $currency->exchange_rate,
            'enabled' => (bool) $currency->enabled,
            'default' => (bool) $currency->default
        ];
    }
}
