<?php

namespace GetCandy\Http\Requests\Api\Currencies;

use GetCandy\Http\Requests\Api\FormRequest;
use GetCandy\Api\Currencies\Models\Currency;

class DeleteRequest extends FormRequest
{
    public function authorize()
    {
        // return $this->user()->can('delete', Currency::class);
        return $this->user()->hasRole('admin');
    }

    public function rules()
    {
        return [
        ];
    }
}
