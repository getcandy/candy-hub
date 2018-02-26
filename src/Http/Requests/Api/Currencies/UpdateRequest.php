<?php

namespace GetCandy\Http\Requests\Api\Currencies;

use GetCandy\Http\Requests\Api\FormRequest;
use GetCandy\Api\Currencies\Models\Currency;

class UpdateRequest extends FormRequest
{
    public function authorize()
    {
        // return $this->user()->can('update', Currency::class);
        return $this->user()->hasRole('admin');
    }
    public function rules(Currency $currency)
    {
        return [
            'code' => 'unique:currencies,code,'. $currency->decodeId($this->currency)
        ];
    }
}
