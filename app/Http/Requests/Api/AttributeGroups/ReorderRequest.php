<?php

namespace GetCandy\Http\Requests\Api\AttributeGroups;

use Auth;
use GetCandy\Http\Requests\Api\FormRequest;
use GetCandy\Api\Attributes\Models\AttributeGroup;
use GetCandy\Api\Attributes\AttributeManager;

class ReorderRequest extends FormRequest
{
    public function authorize()
    {
        // return $this->user()->can('create', AttributeGroup::class);
        return true;
    }

    public function rules(AttributeManager $manager)
    {
        $count = $manager->attributeGroupService()->dataCount();
        return [
            'groups' => 'required|size:' . $count
        ];
    }

    public function messages()
    {
        return [
            'groups.size' => 'You must submit all groups'
        ];
    }
}
