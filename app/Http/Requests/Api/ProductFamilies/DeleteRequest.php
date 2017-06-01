<?php

namespace GetCandy\Http\Requests\Api\ProductFamilies;

use GetCandy\Http\Requests\Api\FormRequest;

class DeleteRequest extends FormRequest
{
    public function authorize()
    {
        // return $this->user()->can('create', Language::class);
        return true;
    }
    public function rules()
    {
        return [
        ];
    }
}
