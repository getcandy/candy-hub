<?php

namespace GetCandy\Http\Requests\Api\Products;

use GetCandy\Http\Requests\Api\FormRequest;

class DeleteRequest extends FormRequest
{
    public function authorize()
    {
        return true;
        // return $this->user()->can('delete', Product::class);
    }

    public function rules()
    {
        return [
            'product' => 'hashid_is_valid:products'
        ];
    }
}
