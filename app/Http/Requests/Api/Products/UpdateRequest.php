<?php

namespace GetCandy\Http\Requests\Api\Products;

use GetCandy\Api\Http\Requests\FormRequest;
use GetCandy\Api\Models\Product;

class UpdateRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('update', Product::class);
    }

    public function rules()
    {
        return [
            'name' => 'required'
        ];
    }
}
