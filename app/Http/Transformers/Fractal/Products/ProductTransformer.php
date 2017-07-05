<?php

namespace GetCandy\Http\Transformers\Fractal\Products;

use GetCandy\Api\Attributes\Models\Attribute;
use GetCandy\Api\Attributes\Models\AttributeGroup;
use GetCandy\Api\Currencies\Models\Currency;
use GetCandy\Api\Languages\Models\Language;
use GetCandy\Api\Products\Models\Product;
use GetCandy\Api\Products\Models\ProductVariant;
use GetCandy\Http\Transformers\Fractal\Attributes\AttributeGroupTransformer;
use GetCandy\Http\Transformers\Fractal\Attributes\AttributeTransformer;
use GetCandy\Http\Transformers\Fractal\BaseTransformer;

class ProductTransformer extends BaseTransformer
{
    protected $attributeGroups;

    protected $availableIncludes = [
        'attribute_groups', 'family', 'layout', 'variants'
    ];

    protected $currency;

    public function transform(Product $product)
    {
        $response = [
            'id' => $product->encodedId(),
            'attribute_data' => $product->attribute_data,
        ];

        return $response;
    }

    public function includeLayout(Product $product)
    {
        return $this->item($product->layout, new LayoutTransformer);
    }

    public function includeFamily(Product $product)
    {
        return $this->item($product->family, new ProductFamilyTransformer);
    }

    public function getAttributeGroups()
    {
        if (!$this->attributeGroups) {
            $this->attributeGroups = AttributeGroup::select('id', 'name', 'handle', 'position')
                ->orderBy('position', 'asc')->with(['attributes'])->get();
        }
        return $this->attributeGroups;
    }
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

    public function includeVariants(Product $product)
    {
        return $this->collection($product->variants, new ProductVariantTransformer);
    }
}
