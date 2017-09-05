<?php

namespace GetCandy\Http\Requests\Api\Categories;

use GetCandy\Http\Requests\Api\FormRequest;
use GetCandy\Api\Categories\Models\Category;

class StoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [];
    }
}
