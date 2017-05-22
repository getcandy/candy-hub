<?php

namespace GetCandy\Http\Requests\Api\Attributes;

use Auth;
use GetCandy\Attributes\Api\Models\AttributeGroup;
use GetCandy\Http\Requests\Api\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize()
    {
        // return $this->user()->can('update', AttributeGroup::class);
        return true;
    }
    public function rules(AttributeGroup $model)
    {
        return [
            'name' => 'required|unique:attribute_groups,name,' . $model->decodeId($this->attribute_group)
        ];
    }
}
