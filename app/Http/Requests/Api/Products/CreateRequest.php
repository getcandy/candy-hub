<?php

namespace GetCandy\Http\Requests\Api\Products;

use GetCandy\Http\Requests\Api\FormRequest;

class CreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // return $this->user()->can('create', Product::class);
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'url' => 'required|unique:routes,slug',
            'stock' => 'required',
            'family_id' => 'required',
            'price' => 'required',
            'sku' => 'required|unique:product_variants,sku'
        ];
    }
}
