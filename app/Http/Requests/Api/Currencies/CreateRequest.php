<?php

namespace GetCandy\Http\Requests\Api\Currencies;

use GetCandy\Http\Requests\Api\FormRequest;
use GetCandy\Api\Currencies\Models\Currency;

class CreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // return $this->user()->can('create', Currency::class);
        return $this->user()->hasRole('admin');
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
            'code' => 'required|unique:currencies,code',
            'enabled' => 'required',
            'exchange_rate' => 'required',
            'format' => 'required'
        ];
    }
}
