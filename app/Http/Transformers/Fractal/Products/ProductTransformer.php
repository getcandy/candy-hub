<?php

namespace GetCandy\Http\Transformers\Fractal\Products;

use GetCandy\Api\Attributes\Models\AttributeGroup;
use GetCandy\Api\Products\Models\Product;
use GetCandy\Api\Traits\IncludesAttributes;
use GetCandy\Http\Transformers\Fractal\Assets\AssetTransformer;
use GetCandy\Http\Transformers\Fractal\Attributes\AttributeGroupTransformer;
use GetCandy\Http\Transformers\Fractal\BaseTransformer;
use GetCandy\Http\Transformers\Fractal\Categories\CategoryTransformer;
use GetCandy\Http\Transformers\Fractal\Channels\ChannelTransformer;
use GetCandy\Http\Transformers\Fractal\Collections\CollectionTransformer;
use GetCandy\Http\Transformers\Fractal\Customers\CustomerGroupTransformer;
use GetCandy\Http\Transformers\Fractal\Layouts\LayoutTransformer;
use GetCandy\Http\Transformers\Fractal\Routes\RouteTransformer;
use PriceCalculator;

class ProductTransformer extends BaseTransformer
{
    use IncludesAttributes;

    /**
     * @var array
     */
    protected $availableIncludes = [
        'assets',
        'associations',
        'attribute_groups',
        'categories',
        'channels',
        'collections',
        'customer_groups',
        'family',
        'layout',
        'routes',
        'variants',
    ];

    /**
     * @param \GetCandy\Api\Products\Models\Product $product
     *
     * @return array
     */
    public function transform(Product $product)
    {
        $this->applyDiscounts($product);
        $response = [
            'id' => $product->encodedId(),
            'attribute_data' => $product->attribute_data,
            'option_data' => $this->parseOptionData($product->option_data),
            'thumbnail' => $this->getThumbnail($product),
            'max_price' => PriceCalculator::get($product->max_price),
            'min_price' => PriceCalculator::get($product->min_price)
        ];

        if ($product->original_min_price) {
            $response['original_min_price'] = (float) PriceCalculator::get($product->original_min_price);
        }

        if ($product->original_max_price) {
            $response['original_max_price'] = (float) PriceCalculator::get($product->original_max_price);
        }

        $response['discounts'] = $product->discounts;
    
        if ($product->pivot && $product->pivot->type) {
            $response['type'] = $product->pivot->type;
        }

        return $response;
    }

    protected function applyDiscounts(Product $product)
    {
        $discounts = app('api')->discounts()->get();
        $sets = app('api')->discounts()->parse($discounts);

        $product->max_price = 0;
        $product->min_price = 0;
        $product->original_max_price = 0;
        $product->original_min_price = 0;

        foreach ($product->variants as $variant) {
            $product->max_price = $variant->price > $product->max_price ? $variant->price : $product->max_price;
            $product->original_max_price = $variant->original_price > $product->original_max_price ? $variant->original_price : $product->original_max_price;
            if ($product->min_price) {
                $product->min_price = $variant->price < $product->min_price ? $variant->price : $product->min_price;
                $product->original_min_price = $variant->original_price < $product->original_min_price ? $variant->original_price : $product->original_min_price;
            } else {
                $product->min_price = $product->max_price;
                $product->original_min_price = $variant->original_price;
            }
        }

        $applied = \Facades\GetCandy\Api\Discounts\Factory::getApplied($sets, \Auth::user(), $product);

        \Facades\GetCandy\Api\Discounts\Factory::apply($applied, $product);
    }

    protected function parseOptionData($data)
    {
        $data = $this->sortOptions($data);
        foreach ($data as $optionKey => $option) {
            $sorted =  $this->sortOptions($option['options']);
            $data[$optionKey]['options'] = $sorted;
        }
        return $data;
    }

    protected function sortOptions($options)
    {
        uasort($options, function ($a, $b) {
            return $a['position'] < $b['position'] ? -1 : 1;
        });
        return $options;
    }

    /**
     * @param \GetCandy\Api\Products\Models\Product $product
     *
     * @return \League\Fractal\Resource\Item
     */
    public function includeLayout(Product $product)
    {
        if (!$product->layout) {
            return null;
        }
        return $this->item($product->layout, new LayoutTransformer);
    }

    /**
     * @param \GetCandy\Api\Products\Models\Product $product
     *
     * @return \League\Fractal\Resource\Item
     */
    public function includeFamily(Product $product)
    {
        if (!$product->family) {
            return null;
        }
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
     * @param \GetCandy\Api\Products\Models\Product $product
     *
     * @return \League\Fractal\Resource\Collection
     */
    public function includeAssociations(Product $product)
    {
        return $this->collection($product->associations, new ProductAssociationTransformer);
    }

    /**
     * @param \GetCandy\Api\Products\Models\Product $product
     *
     * @return \League\Fractal\Resource\Collection
     */
    public function includeAssets(Product $product)
    {
        return $this->collection($product->assets()->orderBy('position', 'asc')->get(), new AssetTransformer);
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
        $channels = app('api')->channels()->getChannelsWithAvailability($product, 'products');
        return $this->collection($channels, new ChannelTransformer);
    }

    /**
     * @param Product $product
     *
     * @return \League\Fractal\Resource\Collection
     */
    public function includeCustomerGroups(Product $product)
    {
        $groups = app('api')->customerGroups()->getGroupsWithAvailability($product, 'products');
        return $this->collection($groups, new CustomerGroupTransformer);
    }

    /**
     * @param Product $product
     *
     * @return \League\Fractal\Resource\Categories
     */
    public function includeCategories(Product $product)
    {
        $categories = app('api')->products()->getCategories($product);
        return $this->collection($categories, new CategoryTransformer);
    }
}
