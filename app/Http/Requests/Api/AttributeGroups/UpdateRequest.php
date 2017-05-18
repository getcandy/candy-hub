<?php

namespace GetCandy\Http\Requests\Api\AttributeGroups;

use Auth;
use GetCandy\Http\Requests\Api\FormRequest;
use GetCandy\Api\Attributes\AttributeManager;

class UpdateRequest extends FormRequest
{
    protected $hashid = HashidService::class;

    public function authorize()
    {
        // return $this->user()->can('update', AttributeGroup::class);
        return true;
    }
    public function rules(AttributeManager $manager)
    {
        $service = $manager->attributeGroupService();
        return [
            'name' => 'required|unique:attribute_groups,name,' . $service->dataDecodeId($this->attribute_group)
        ];
    }
}
