<?php

namespace GetCandy\Api;

use GetCandy\Api\Services\ProductService;
use GetCandy\Api\Services\AttributeService;
use GetCandy\Api\Services\AttributeGroupService;
use GetCandy\Api\Services\LanguageService;
use GetCandy\Api\Services\ChannelService;

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
     * @var ChannelService
     */
    protected $channels;

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
        ChannelService $channels,
        LanguageService $languages,
        ProductService $products
    ) {
        $this->attributeGroups = $attributeGroups;
        $this->attributes = $attributeGroups;
        $this->channels = $channels;
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

    public function channels()
    {
        return $this->channels;
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
