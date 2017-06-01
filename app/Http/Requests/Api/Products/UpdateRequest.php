<?php

namespace GetCandy\Http\Requests\Api\Products;

use GetCandy\Http\Requests\Api\FormRequest;
use GetCandy\Api\Products\Models\Product;

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
            'name' => 'required',
            'family_id' => 'hashid_is_valid:product_families'
        ];
    }
}
