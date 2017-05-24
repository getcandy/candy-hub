<?php

namespace GetCandy\Http\Requests\Api\Taxes;

use GetCandy\Http\Requests\Api\FormRequest;
use GetCandy\Api\Models\Tax;

class UpdateRequest extends FormRequest
{
    public function authorize()
    {
        // return $this->user()->can('update', Tax::class);
        return true;
    }
    public function rules(Tax $tax)
    {
        return [
            'name' => 'unique:taxes,name,'. $tax->decodeId($this->tax)
        ];
    }
}
