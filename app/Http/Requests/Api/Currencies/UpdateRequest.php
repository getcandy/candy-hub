<?php

namespace GetCandy\Http\Requests\Api\Currencies;

use GetCandy\Http\Requests\Api\FormRequest;
use GetCandy\Api\Models\Currency;

class UpdateRequest extends FormRequest
{
    public function authorize()
    {
        // return $this->user()->can('update', Currency::class);
        return true;
    }
    public function rules(Currency $currency)
    {
        return [
            'code' => 'unique:currencies,code,'. $currency->decodeId($this->currency)
        ];
    }
}
