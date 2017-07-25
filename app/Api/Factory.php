<?php

namespace GetCandy\Api;

use GetCandy\Api\Attributes\Services\AttributeGroupService;
use GetCandy\Api\Attributes\Services\AttributeService;
use GetCandy\Api\Auth\Services\UserService;
use GetCandy\Api\Categories\Services\CategoryService;
use GetCandy\Api\Channels\Services\ChannelService;
use GetCandy\Api\Collections\Services\CollectionService;
use GetCandy\Api\Currencies\Services\CurrencyService;
use GetCandy\Api\Customers\Services\CustomerService;
use GetCandy\Api\Customers\Services\CustomerGroupService;
use GetCandy\Api\Languages\Services\LanguageService;
use GetCandy\Api\Layouts\Services\LayoutService;
use GetCandy\Api\Pages\Services\PageService;
use GetCandy\Api\Products\Services\ProductFamilyService;
use GetCandy\Api\Products\Services\ProductService;
use GetCandy\Api\Products\Services\ProductVariantService;
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
     * @var CategoryService
     */
    protected $categories;

    /**
     * @var ChannelService
     */
    protected $channels;

    /**
     * @var CurrencyService
     */
    protected $currencies;

    /**
     * @var CustomerService
     */
    protected $customers;

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
     * @var ProductVariantService
     */
    protected $productVariants;

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
        CategoryService $categories,
        ChannelService $channels,
        CollectionService $collections,
        CurrencyService $currencies,
        CustomerService $customers,
        CustomerGroupService $customerGroups,
        LanguageService $languages,
        LayoutService $layouts,
        PageService $pages,
        ProductFamilyService $productFamilies,
        ProductVariantService $productVariants,
        ProductService $products,
        RouteService $routes,
        TaxService $taxes,
        UserService $users
    ) {
        $this->attributeGroups = $attributeGroups;
        $this->attributes = $attributes;
        $this->categories = $categories;
        $this->channels = $channels;
        $this->collections = $collections;
        $this->currencies = $currencies;
        $this->customers = $customers;
        $this->customerGroups = $customerGroups;
        $this->languages = $languages;
        $this->layouts = $layouts;
        $this->pages = $pages;
        $this->productFamilies = $productFamilies;
        $this->products = $products;
        $this->productVariants = $productVariants;
        $this->routes = $routes;
        $this->taxes = $taxes;
        $this->users = $users;
    }

    public function __call($name, $arguments)
    {
        if (!property_exists($this, $name)) {
            throw new \GetCandy\Exceptions\InvalidServiceException(trans('exceptions.invalid_service', [
                'service' => $name
            ]), 1);
        }
        return $this->{$name};
    }
}
