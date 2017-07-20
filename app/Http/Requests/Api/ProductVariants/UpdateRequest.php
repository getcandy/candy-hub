<?php

namespace GetCandy\Http\Requests\Api\ProductVariants;

use Illuminate\Foundation\Http\FormRequest;
use GetCandy\Api\Products\Models\ProductVariant;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(ProductVariant $variant)
    {
        return [
            'sku' => 'required|unique:product_variants,sku,'. $variant->decodeId($this->variant)
        ];
    }
}
