<?php

namespace GetCandy\Http\Requests\Api\AttributeGroups;

use Auth;
use GetCandy\Http\Requests\Api\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize()
    {
        // return $this->user()->can('update', AttributeGroup::class);
        return $this->user()->hasRole('admin');
    }
    public function rules()
    {
        $service = app('api')->attributeGroups();
        return [
            'name' => 'required|unique:attribute_groups,name,' . $service->getDecodedId($this->attribute_group)
        ];
    }
}
