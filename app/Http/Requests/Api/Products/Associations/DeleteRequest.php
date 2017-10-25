<?php

namespace GetCandy\Http\Requests\Api\Products\Associations;

use GetCandy\Http\Requests\Api\FormRequest;

class DeleteRequest extends FormRequest
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
            'associations' => 'required|hashid_is_valid:products'
        ];
    }
}
