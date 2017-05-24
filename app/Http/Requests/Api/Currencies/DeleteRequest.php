<?php

namespace GetCandy\Api\Http\Requests\Currencies;

use GetCandy\Api\Http\Requests\FormRequest;
use GetCandy\Api\Models\Currency;

class DeleteRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('delete', Currency::class);
    }

    public function rules()
    {
        return [
        ];
    }
}
