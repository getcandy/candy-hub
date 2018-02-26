<?php

namespace GetCandy\Http\Requests\Api\Products;

use GetCandy\Http\Requests\Api\FormRequest;
use GetCandy\Api\Products\Models\Product;

class UpdateCollectionsRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->hasRole('admin');
    }

    public function rules()
    {
        return [
            'collections' => 'required|array'
        ];
    }
}
