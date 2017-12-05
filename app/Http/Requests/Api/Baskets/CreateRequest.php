<?php
namespace GetCandy\Http\Requests\Api\Baskets;

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
        // return $this->user()->can('create', Category::class);
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
            'variants' => 'required|array|unique_lines',
            'variants.*.id' => 'required|hashid_is_valid:product_variants',
            'variants.*.price' => 'required|numeric',
            'variants.*.quantity' => 'required|numeric|min:1',
            'basket_id' => 'hashid_is_valid:baskets'
        ];
    }
}
