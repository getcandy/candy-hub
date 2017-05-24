<?php

namespace GetCandy\Api;

use GetCandy\Api\Services\AttributeGroupService;
use GetCandy\Api\Services\AttributeService;
use GetCandy\Api\Services\ChannelService;
use GetCandy\Api\Services\CurrencyService;
use GetCandy\Api\Services\LanguageService;
use GetCandy\Api\Services\ProductService;
use GetCandy\Api\Services\TaxService;

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
     * @var CurrencyService
     */
    protected $currencies;

    /**
     * @var LanguageService
     */
    protected $languages;

    /**
     * @var ProductService
     */
    protected $products;

    /**
     * @var TaxService
     */
    protected $taxes;

    public function __construct(
        AttributeGroupService $attributeGroups,
        AttributeService $attributes,
        ChannelService $channels,
        CurrencyService $currencies,
        LanguageService $languages,
        ProductService $products,
        TaxService $taxes
    ) {
        $this->attributeGroups = $attributeGroups;
        $this->attributes = $attributeGroups;
        $this->channels = $channels;
        $this->currencies = $currencies;
        $this->languages = $languages;
        $this->products = $products;
        $this->taxes = $taxes;
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

    public function currencies()
    {
        return $this->currencies;
    }

    public function languages()
    {
        return $this->languages;
    }

    public function products()
    {
        return $this->products;
    }

    public function taxes()
    {
        return $this->taxes;
    }
}
