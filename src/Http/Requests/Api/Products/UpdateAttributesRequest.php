<?php

namespace GetCandy\Http\Requests\Api\Products;

use GetCandy\Http\Requests\Api\FormRequest;
use GetCandy\Api\Products\Models\Product;

class UpdateAttributesRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->hasRole('admin');
    }

    public function rules()
    {
        return [
            'attributes' => 'required|array'
        ];
    }
}
