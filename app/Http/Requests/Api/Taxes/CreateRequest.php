<?php

namespace GetCandy\Http\Requests\Api\Taxes;

use GetCandy\Http\Requests\Api\FormRequest;
use GetCandy\Api\Models\Tax;

class CreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // return $this->user()->can('create', Tax::class);
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
            'name' => 'required|unique:taxes,name',
            'percentage' => 'required'
        ];
    }
}
