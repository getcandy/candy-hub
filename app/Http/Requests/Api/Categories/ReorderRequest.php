<?php

namespace GetCandy\Http\Requests\Api\Categories;

use GetCandy\Http\Requests\Api\FormRequest;
use GetCandy\Api\Categories\Models\Category;

class ReorderRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'node'          => 'required',
            'moved-node'    => 'required',
            'action'        => 'required'
        ];
    }
}
