<?php

namespace GetCandy\Api;

use GetCandy\Api\Assets\Services\AssetService;
use GetCandy\Api\Assets\Services\AssetSourceService;
use GetCandy\Api\Assets\Services\AssetTransformService;
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
use GetCandy\Api\Products\Services\ProductCategoryService;
use GetCandy\Api\Products\Services\ProductFamilyService;
use GetCandy\Api\Products\Services\ProductService;
use GetCandy\Api\Products\Services\ProductVariantService;
use GetCandy\Api\Routes\Services\RouteService;
use GetCandy\Api\Auth\Services\RoleService;
use GetCandy\Api\Search\Services\SearchService;
use GetCandy\Api\Settings\Services\SettingService;
use GetCandy\Api\Tags\Services\TagService;
use GetCandy\Api\Taxes\Services\TaxService;

class Factory
{
    /**
     * @var AssetService
     */
    protected $assets;

    /**
     * @var \GetCandy\Api\Assets\Services\AssetSourceService
     */
    protected $assetSources;

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
     * @var RoleService
     */
    protected $roles;

    /**
     * @var \GetCandy\Api\Services\SearchService;
     */
    protected $search;

    /**
     * @var \GetCandy\Api\SettingService
     */
    protected $settings;

    /**
     * @var TagService
     */
    protected $tags;

    /**
     * @var TaxService
     */
    protected $taxes;

    /**
     * @var \GetCandy\Api\Assets\Services\AssetTransformService
     */
    protected $transforms;

    /**
     * @var UserService
     */
    protected $users;

    public function __construct(
        AssetService $assets,
        AssetSourceService $assetSources,
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
        ProductCategoryService $productCategories,
        ProductFamilyService $productFamilies,
        ProductVariantService $productVariants,
        ProductService $products,
        RouteService $routes,
        RoleService $roles,
        SearchService $search,
        SettingService $settings,
        TagService $tags,
        TaxService $taxes,
        AssetTransformService $transforms,
        UserService $users
    ) {
        $this->assets = $assets;
        $this->assetSources = $assetSources;
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
        $this->productCategories = $productCategories;
        $this->productVariants = $productVariants;
        $this->routes = $routes;
        $this->roles = $roles;
        $this->search = $search;
        $this->settings = $settings;
        $this->tags = $tags;
        $this->taxes = $taxes;
        $this->transforms = $transforms;
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
