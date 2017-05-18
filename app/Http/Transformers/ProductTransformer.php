<?php

namespace GetCandy\Http\Transformers;

use GetCandy\Api\Attributes\Models\AttributeGroup;
use GetCandy\Api\Currencies\Models\Currency;
use GetCandy\Api\Languages\Models\Language;
use GetCandy\Api\Products\Models\Attribute;
use GetCandy\Api\Products\Models\Product;
use League\Fractal\TransformerAbstract;

class ProductTransformer extends TransformerAbstract
{
    protected $attributeGroups;

    protected $availableIncludes = [
        'attribute_groups', 'family'
    ];

    protected $currency;

    public function transform(Product $product)
    {
        $response = [
            'id' => $product->encodedId(),
            'name' => $this->getName($product->name),
            'price' => $this->getPrice($product->price)
        ];
        return $response;
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

    protected function setCurrency($currency)
    {
        $this->currency = $currency;
        return $this;
    }

    protected function getPrice($price)
    {
        while (!$this->currency) {
            $this->setCurrency(Currency::where('default', '=', true)->first());
            if (app('request')->currency) {
                $currency = Currency::where('code', '=', strtoupper(app('request')->currency))->first();
                if ($currency) {
                    $this->setCurrency($currency);
                }
            }
        }

        $price = $price * $this->currency->exchange_rate;
        $price = number_format($price, 2, ($this->currency->decimal_point ?: ' '), ($this->currency->thousand_point ?: ' '));
        $price = str_replace(':price', $price, $this->currency->format);
        return $price;
    }

    protected function getName($name)
    {
        $name = json_decode($name, true);
        $locale = app()->getLocale();

        $requestLang = strtolower(app('request')->languages);
        if ($requestLang) {
            if ($requestLang != 'all') {
                // $languages = explode(',', $requestLang);
                // foreach ($)
            }
        } else {
            if (!empty($name[$locale])) {
                $name = $name[$locale];
            } else {
                $name = $name[$defaultLocale];
            }
        }
        return $name;
    }
}
