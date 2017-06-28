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
            'attributes' => 'required|array',
            'family_id' => 'required|hashid_is_valid:product_families',
            'layout_id' => 'required|hashid_is_valid:layouts',
            'sku' => 'required|unique:product_variants,sku'
        ];
    }
}
