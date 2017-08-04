<?php

namespace GetCandy\Http\Transformers\Fractal\Products;

use GetCandy\Api\Attributes\Models\AttributeGroup;
use GetCandy\Api\Products\Models\Product;
use GetCandy\Http\Transformers\Fractal\Assets\AssetTransformer;
use GetCandy\Http\Transformers\Fractal\Attributes\AttributeGroupTransformer;
use GetCandy\Http\Transformers\Fractal\BaseTransformer;
use GetCandy\Http\Transformers\Fractal\Channels\ChannelTransformer;
use GetCandy\Http\Transformers\Fractal\Collections\CollectionTransformer;
use GetCandy\Http\Transformers\Fractal\Customers\CustomerGroupTransformer;
use GetCandy\Http\Transformers\Fractal\Layouts\LayoutTransformer;
use GetCandy\Http\Transformers\Fractal\Routes\RouteTransformer;

class ProductTransformer extends BaseTransformer
{
    /**
     * @var
     */
    protected $attributeGroups;

    /**
     * @var array
     */
    protected $availableIncludes = [
        'attribute_groups',
        'family',
        'layout',
        'variants',
        'collections',
        'routes',
        'channels',
        'customer_groups',
        'assets'
    ];

    /**
     * @param \GetCandy\Api\Products\Models\Product $product
     *
     * @return array
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
     * @param \GetCandy\Api\Products\Models\Product $product
     *
     * @return \League\Fractal\Resource\Item
     */
    public function includeLayout(Product $product)
    {
        return $this->item($product->layout, new LayoutTransformer);
    }

    /**
     * @param \GetCandy\Api\Products\Models\Product $product
     *
     * @return \League\Fractal\Resource\Item
     */
    public function includeFamily(Product $product)
    {
        return $this->item($product->family, new ProductFamilyTransformer);
    }

    /**
     * @param \GetCandy\Api\Products\Models\Product $product
     *
     * @return \League\Fractal\Resource\Collection
     */
    public function includeCollections(Product $product)
    {
        return $this->collection($product->collections, new CollectionTransformer);
    }


    /**
     * @return mixed
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
     * @param \GetCandy\Api\Products\Models\Product $product
     *
     * @return \League\Fractal\Resource\Collection
     */
    public function includeAssets(Product $product)
    {
        return $this->collection($product->assets, new AssetTransformer);
    }

    /**
     * @param \GetCandy\Api\Products\Models\Product $product
     *
     * @return \League\Fractal\Resource\Collection
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
     *
     * @param  Product $product
     *
     * @return \League\Fractal\Resource\Collection
     */
    public function includeVariants(Product $product)
    {
        return $this->collection($product->variants, new ProductVariantTransformer);
    }

    /**
     * @param Product $product
     *
     * @return \League\Fractal\Resource\Collection
     */
    public function includeRoutes(Product $product)
    {
        return $this->collection($product->routes, new RouteTransformer);
    }

    /**
     * @param Product $product
     *
     * @return \League\Fractal\Resource\Collection
     */
    public function includeChannels(Product $product)
    {
        $channels = app('api')->channels()->getChannelsWithAvailability($product);
        return $this->collection($channels, new ChannelTransformer);
    }

    /**
     * @param Product $product
     *
     * @return \League\Fractal\Resource\Collection
     */
    public function includeCustomerGroups(Product $product)
    {
        $groups = app('api')->customerGroups()->getGroupsWithAvailability($product);
        return $this->collection($groups, new CustomerGroupTransformer);
    }
}
