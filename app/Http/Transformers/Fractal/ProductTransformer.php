<?php

namespace GetCandy\Http\Transformers\Fractal;

use GetCandy\Api\Attributes\Models\Attribute;
use GetCandy\Api\Attributes\Models\AttributeGroup;
use GetCandy\Api\Currencies\Models\Currency;
use GetCandy\Api\Languages\Models\Language;
use GetCandy\Api\Products\Models\Product;

class ProductTransformer extends BaseTransformer
{
    protected $attributeGroups;

    protected $availableIncludes = [
        'attribute_groups', 'family', 'layout'
    ];

    protected $currency;

    public function transform(Product $product)
    {
        $response = [
            'id' => $product->encodedId(),
            'name' => $this->getLocalisedName($product->name),
        ];

        if ($product->attribute_data) {
            $attribute_data = [];
            foreach ($product->attribute_data as $handle => $attribute) {
                $attribute_data[$handle] = $this->getLocalisedName($attribute);
            }
            $response['attribute_data'] = $attribute_data;
        }

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

    // protected function setCurrency($currency)
    // {
    //     $this->currency = $currency;
    //     return $this;
    // }

    // protected function getPrice($price)
    // {
    //     while (!$this->currency) {
    //         $this->setCurrency(Currency::where('default', '=', true)->first());
    //         if (app('request')->currency) {
    //             $currency = Currency::where('code', '=', strtoupper(app('request')->currency))->first();
    //             if ($currency) {
    //                 $this->setCurrency($currency);
    //             }
    //         }
    //     }

    //     $price = $price * $this->currency->exchange_rate;
    //     $price = number_format($price, 2, ($this->currency->decimal_point ?: ' '), ($this->currency->thousand_point ?: ' '));
    //     $price = str_replace('{price}', $price, $this->currency->format);
    //     return $price;
    // }
}
