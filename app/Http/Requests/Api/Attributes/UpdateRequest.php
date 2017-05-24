<?php

namespace GetCandy\Http\Requests\Api\Attributes;

use Auth;
use GetCandy\Http\Requests\Api\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize()
    {
        // return $this->user()->can('update', AttributeGroup::class);
        return true;
    }
    public function rules()
    {
        return [
            'name' => 'required|unique:attribute_groups,name,' . app('api')->attributeGroups()->getDecodedId($this->attribute_group)
        ];
    }
}
