<?php

namespace GetCandy\Api;

use GetCandy\Api\Services\ProductService;
use GetCandy\Api\Services\AttributeService;
use GetCandy\Api\Services\AttributeGroupService;
use GetCandy\Api\Services\LanguageService;

class Factory
{
    /**
     * @var AttributeService
     */
    protected $attributes;

    /**
     * @var AttributeGroupService
     */
    protected $attributeGroups;

    /**
     * @var LanguageService
     */
    protected $languages;

    /**
     * @var ProductService
     */
    protected $products;

    public function __construct(
        AttributeGroupService $attributeGroups,
        AttributeService $attributes,
        LanguageService $languages,
        ProductService $products
    ) {
        $this->attributeGroups = $attributeGroups;
        $this->attributes = $attributeGroups;
        $this->languages = $languages;
        $this->products = $products;
    }

    public function attributes()
    {
        return $this->attributes;
    }

    public function attributeGroups()
    {
        return $this->attributeGroups;
    }

    public function languages()
    {
        return $this->languages;
    }

    public function products()
    {
        return $this->products;
    }
}
