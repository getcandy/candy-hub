<?php

namespace GetCandy\Http\Requests\Api\Products;

use GetCandy\Http\Requests\Api\FormRequest;
use GetCandy\Api\Models\Product;

class UpdateRequest extends FormRequest
{
    public function authorize()
    {
        // return $this->user()->can('update', Product::class);
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required'
        ];
    }
}
