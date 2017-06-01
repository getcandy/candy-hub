<?php

namespace GetCandy\Http\Requests\Api\Taxes;

use GetCandy\Http\Requests\Api\FormRequest;
use GetCandy\Api\Taxes\Models\Tax;

class DeleteRequest extends FormRequest
{
    public function authorize()
    {
        // return $this->user()->can('delete', Tax::class);
        return true;
    }

    public function rules()
    {
        return [
        ];
    }
}
