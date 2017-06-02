<?php

namespace GetCandy\Api;

use GetCandy\Api\Attributes\Services\AttributeGroupService;
use GetCandy\Api\Attributes\Services\AttributeService;
use GetCandy\Api\Auth\Services\UserService;
use GetCandy\Api\Channels\Services\ChannelService;
use GetCandy\Api\Currencies\Services\CurrencyService;
use GetCandy\Api\Languages\Services\LanguageService;
use GetCandy\Api\Pages\Services\PageService;
use GetCandy\Api\Products\Services\ProductFamilyService;
use GetCandy\Api\Products\Services\ProductService;
use GetCandy\Api\Taxes\Services\TaxService;

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
     * @var PageService
     */
    protected $pages;

    /**
     * @var ProductService
     */
    protected $products;

    /**
     * @var ProductFamilyService
     */
    protected $productFamilies;

    /**
     * @var TaxService
     */
    protected $taxes;

    /**
     * @var UserService
     */
    protected $users;

    public function __construct(
        AttributeGroupService $attributeGroups,
        AttributeService $attributes,
        ChannelService $channels,
        CurrencyService $currencies,
        LanguageService $languages,
        PageService $pages,
        ProductFamilyService $productFamilies,
        ProductService $products,
        TaxService $taxes,
        UserService $users
    ) {
        $this->attributeGroups = $attributeGroups;
        $this->attributes = $attributes;
        $this->channels = $channels;
        $this->currencies = $currencies;
        $this->languages = $languages;
        $this->pages = $pages;
        $this->productFamilies = $productFamilies;
        $this->products = $products;
        $this->taxes = $taxes;
        $this->users = $users;
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

    public function pages()
    {
        return $this->pages;
    }

    public function products()
    {
        return $this->products;
    }

    public function productFamilies()
    {
        return $this->productFamilies;
    }

    public function taxes()
    {
        return $this->taxes;
    }

    public function users()
    {
        return $this->users;
    }
}
