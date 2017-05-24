<?php

namespace GetCandy\Http\Requests\Api\AttributeGroups;

use Auth;
use GetCandy\Http\Requests\Api\FormRequest;

class DeleteRequest extends FormRequest
{
    public function authorize()
    {
        // return $this->user()->can('create', Attribute::class);
        return true;
    }
    public function rules()
    {
        return [
            'delete_attributes' => 'required_without:group_id',
            'group_id' => 'required_without:delete_attributes',
        ];
    }
}
