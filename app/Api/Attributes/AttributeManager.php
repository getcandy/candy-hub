<?php

namespace GetCandy\Api\Attributes;

use GetCandy\Api\Attributes\Services\AttributeGroupService;
use GetCandy\Api\Attributes\Services\AttributeService;

class AttributeManager
{
    protected $attributeService;
    protected $attributeGroupService;

    public function __construct(
        AttributeService $attributeService,
        AttributeGroupService $attributeGroupService
    ) {
        $this->attributeService = $attributeService;
        $this->attributeGroupService = $attributeGroupService;
    }

    public function attributes()
    {
        return $this->attributeService;
    }

    public function attributeGroups()
    {
        return $this->attributeGroupService;
    }
}
