<?php

namespace GetCandy\Http\Transformers\Fractal\Products;

use GetCandy\Api\Attributes\Models\Attribute;
use GetCandy\Api\Attributes\Models\AttributeGroup;
use GetCandy\Api\Currencies\Models\Currency;
use GetCandy\Api\Languages\Models\Language;
use GetCandy\Api\Products\Models\Product;
use GetCandy\Api\Products\Models\ProductVariant;
use GetCandy\Http\Transformers\Fractal\Attributes\AttributeGroupTransformer;
use GetCandy\Http\Transformers\Fractal\Channels\ChannelTransformer;
use GetCandy\Http\Transformers\Fractal\Attributes\AttributeTransformer;
use GetCandy\Http\Transformers\Fractal\Customers\CustomerGroupTransformer;
use GetCandy\Http\Transformers\Fractal\Routes\RouteTransformer;
use GetCandy\Http\Transformers\Fractal\BaseTransformer;
use GetCandy\Http\Transformers\Fractal\Collections\CollectionTransformer;
use GetCandy\Http\Transformers\Fractal\Layouts\LayoutTransformer;

class ProductTransformer extends BaseTransformer
{
    /**
     * @var League\Fractal\Resource\Collection
     */
    protected $attributeGroups;

    /**
     * @var Array
     */
    protected $availableIncludes = [
        'attribute_groups', 'family', 'layout', 'variants', 'collections', 'routes', 'channels', 'customer_groups'
    ];

    /**
     * Decorates the product object for viewing
     * @param  Product $product
     * @return Array
     */
    public function transform(Product $product)
    {
        $response = [
            'id' => $product->encodedId(),
            'attribute_data' => $product->attribute_data,
        ];

        return $response;
    }

    /**
     * Includes the layout associated to the product
     * @param  Product $product
     * @return League\Fractal\Resource\Collection
     */
    public function includeLayout(Product $product)
    {
        return $this->item($product->layout, new LayoutTransformer);
    }

    /**
     * Includes the product family
     * @param  Product $product
     * @return League\Fractal\Resource\Collection
     */
    public function includeFamily(Product $product)
    {
        return $this->item($product->family, new ProductFamilyTransformer);
    }

    /**
     * Includes any collections associated to the product
     * @param  Product $product
     * @return League\Fractal\Resource\Collection
     */
    public function includeCollections(Product $product)
    {
        return $this->collection($product->collections, new CollectionTransformer);
    }

    /**
     * Gets all the attribute groups associated to the product
     * @return League\Fractal\Resource\Collection
     */
    public function getAttributeGroups()
    {
        if (!$this->attributeGroups) {
            $this->attributeGroups = AttributeGroup::select('id', 'name', 'handle', 'position')
                ->orderBy('position', 'asc')->with(['attributes'])->get();
        }
        return $this->attributeGroups;
    }

    /**
     * Includes any attribute groups related to the product
     * @param  Product $product
     * @return League\Fractal\Resource\Collection
     */
    public function includeAttributeGroups(Product $product)
    {
        $attributeIds = array_merge(
            $product->attributes->pluck('id')->toArray(),
            $product->family->attributes->pluck('id')->toArray()
        );
        $attributeGroups = $this->getAttributeGroups()->filter(function ($group) use ($attributeIds) {
            if ($group->attributes->whereIn('id', $attributeIds)->count()) {
                return $group;
            }
        });
        return $this->collection($attributeGroups, new AttributeGroupTransformer);
    }

    /**
     * Includes any product variants
     * @param  Product $product
     * @return \League\Fractal\Resource\Collection
     */
    public function includeVariants(Product $product)
    {
        return $this->collection($product->variants, new ProductVariantTransformer);
    }

    /**
     * @param Product $product
     * @return \League\Fractal\Resource\Collection
     */
    public function includeRoutes(Product $product)
    {
        return $this->collection($product->routes, new RouteTransformer);
    }

    /**
     * @param Product $product
     * @return \League\Fractal\Resource\Collection
     */
    public function includeChannels(Product $product)
    {
        $channels = app('api')->channels()->getChannelsWithAvailability($product);
        return $this->collection($channels, new ChannelTransformer);
    }

    /**
     * @param Product $product
     * @return \League\Fractal\Resource\Collection
     */
    public function includeCustomerGroups(Product $product)
    {
        $groups = app('api')->customerGroups()->getGroupsWithAvailability($product);
        return $this->collection($groups, new CustomerGroupTransformer);
    }
}
