<?php

namespace GetCandy\Http\Requests\Api\Products;

use GetCandy\Http\Requests\Api\FormRequest;
use GetCandy\Api\Products\Models\Product;

class UpdateCategoriesRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->hasRole('admin');
    }

    public function rules()
    {
        return [
            'categories' => 'required|array|min:1',
            'categories.*' => 'required|hashid_is_valid:categories'
        ];
    }

    public function attributes()
    {
        return [
            'categories.*' => 'category'
        ];
    }
}
