<?php

namespace GetCandy\Http\Requests\Api\ProductFamilies;

use GetCandy\Http\Requests\Api\FormRequest;

class CreateRequest extends FormRequest
{
    public function authorize()
    {
        // return $this->user()->can('create', Language::class);
        return true;
    }
    public function rules()
    {
        return [
            'name' => 'required|unique:product_families,name',
        ];
    }
}
