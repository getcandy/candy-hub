<?php

namespace GetCandy\Http\Requests\Api\Attributes;

use Auth;
use GetCandy\Http\Requests\Api\FormRequest;
use GetCandy\Api\Attributes\Models\Attribute;

class CreateRequest extends FormRequest
{
    public function authorize()
    {
        // return $this->user()->can('create', Attribute::class);
        return true;
    }
    public function rules(Attribute $attribute)
    {
        return [
            'group_id' => 'required',
            'name' => 'required|unique:attributes,name',
        ];
    }
}
