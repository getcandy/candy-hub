<?php

namespace GetCandy\Http\Requests\Api\AttributeGroups;

use Auth;
use GetCandy\Http\Requests\Api\FormRequest;

class CreateRequest extends FormRequest
{
    public function authorize()
    {
        // return $this->user()->can('create', Attribute::class);
        return $this->user()->hasRole('admin');
    }
    public function rules()
    {
        return [
            'name' => 'required|unique:attribute_groups,name',
            'handle' => 'required|unique:attribute_groups,handle'
        ];
    }
}
