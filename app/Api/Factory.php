<?php

namespace GetCandy\Api;

use GetCandy\Api\Attributes\Services\AttributeGroupService;
use GetCandy\Api\Attributes\Services\AttributeService;
use GetCandy\Api\Auth\Services\UserService;
use GetCandy\Api\Channels\Services\ChannelService;
use GetCandy\Api\Currencies\Services\CurrencyService;
use GetCandy\Api\Languages\Services\LanguageService;
use GetCandy\Api\Layouts\Services\LayoutService;
use GetCandy\Api\Pages\Services\PageService;
use GetCandy\Api\Products\Services\ProductFamilyService;
use GetCandy\Api\Products\Services\ProductService;
use GetCandy\Api\Routes\Services\RouteService;
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
     * @var LayoutService
     */
    protected $layouts;

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
     * @var RouteService
     */
    protected $routes;

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
        LayoutService $layouts,
        LanguageService $languages,
        PageService $pages,
        ProductFamilyService $productFamilies,
        ProductService $products,
        RouteService $routes,
        TaxService $taxes,
        UserService $users
    ) {
        $this->attributeGroups = $attributeGroups;
        $this->attributes = $attributes;
        $this->channels = $channels;
        $this->currencies = $currencies;
        $this->layouts = $layouts;
        $this->languages = $languages;
        $this->pages = $pages;
        $this->productFamilies = $productFamilies;
        $this->products = $products;
        $this->routes = $routes;
        $this->taxes = $taxes;
        $this->users = $users;
    }

    public function __call($name, $arguments)
    {
        if (property_exists($this, $name)) {
            return $this->{$name};
        }
    }
}
