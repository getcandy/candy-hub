<?php

namespace GetCandy\Http\Transformers\Fractal\Categories;

use GetCandy\Api\Attributes\Models\AttributeGroup;
use GetCandy\Api\Categories\Models\Category;
use GetCandy\Http\Transformers\Fractal\Assets\AssetTransformer;
use GetCandy\Http\Transformers\Fractal\Attributes\AttributeGroupTransformer;
use GetCandy\Http\Transformers\Fractal\BaseTransformer;
use GetCandy\Http\Transformers\Fractal\Channels\ChannelTransformer;
use GetCandy\Http\Transformers\Fractal\Customers\CustomerGroupTransformer;
use GetCandy\Http\Transformers\Fractal\Routes\RouteTransformer;

class CategoryTransformer extends BaseTransformer
{
    protected $attributeGroups;

    protected $defaultIncludes = [
        'routes'
    ];
    protected $availableIncludes = [
        'attribute_groups','assets', 'children', 'channels', 'customer_groups'
    ];

    public function transform(Category $category)
    {
        $data = [
            'id' => $category->encodedId(),
            'attribute_data' => $category->attribute_data,
            'depth' => $category->depth,
            'product_count' => $category->getProductCount(),
            'lazy' => $category->hasChildren(),
            'hasChildren' => $category->hasChildren(),
            'thumbnail' => $this->getThumbnail($category)
        ];

        if (!is_null($category->aggregate_selected)) {
            $data['aggregate_selected'] = $category->aggregate_selected;
        }
/*
        $children = [];

        $i = 0;
        while ($category->children->count() > $i) {
            $transformer = $this->item($category->children->offsetGet($i), new CategoryTransformer);
            $children[] = app()->fractal->createData($transformer)->toArray();
            $i++;
        }

        $data['children'] = $children;
*/
        return $data;
    }
    public function includeChildren(Category $category)
    {
        return $this->collection($category->children, $this);
    }

    public function includeRoutes(Category $category)
    {
        return $this->collection($category->routes, new RouteTransformer);
    }

    /**
     * @param Category $category
     *
     * @return \League\Fractal\Resource\Collection
     */
    public function includeChannels(Category $category)
    {
        $channels = app('api')->channels()->getChannelsWithAvailability($category, 'categories');
        return $this->collection($channels, new ChannelTransformer);
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
    public function includeAttributeGroups(Category $category)
    {
        $attributeIds = $category->attributes->pluck('id')->toArray();

        if ($category->family) {
            $attributeIds = array_merge(
                $attributeIds,
                $category->family->attributes->pluck('id')->toArray()
            );
        }

        $attributeGroups = $this->getAttributeGroups()->filter(function ($group) use ($attributeIds) {
            if ($group->attributes->whereIn('id', $attributeIds)->count()) {
                return $group;
            }
        });
        return $this->collection($attributeGroups, new AttributeGroupTransformer);
    }

    public function includeAssets(Category $category)
    {
        return $this->collection($category->assets()->orderBy('position', 'asc')->get(), new AssetTransformer);
    }

    /**
     * @param Product $product
     *
     * @return \League\Fractal\Resource\Collection
     */
    public function includeCustomerGroups(Category $category)
    {
        $groups = app('api')->customerGroups()->getGroupsWithAvailability($category, 'categories');
        return $this->collection($groups, new CustomerGroupTransformer);
    }
}
