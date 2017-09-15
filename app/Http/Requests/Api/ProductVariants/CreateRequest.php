<?php

namespace GetCandy\Http\Requests\Api\ProductVariants;

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
            'variants.*.sku' => 'required|unique:product_variants'
        ];
    }

    public function messages()
    {
        return [
            'variants.*.sku.unique' => 'This SKU has already been taken',
            'variants.*.sku.required' => 'The SKU field is required',
        ];
    }
}
