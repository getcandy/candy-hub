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

    public function attributeService()
    {
        return $this->attributeService;
    }

    public function attributeGroupService()
    {
        return $this->attributeGroupService;
    }
}
