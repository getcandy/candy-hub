<?php

namespace GetCandy\Http\Transformers\Fractal;

use GetCandy\Api\Attributes\Models\Attribute;
use GetCandy\Http\Transformers\Fractal\AttributeGroupTransformer;

class AttributeTransformer extends BaseTransformer
{
    protected $availableIncludes = [
        'group'
    ];

    public function transform(Attribute $attribute)
    {
        return [
            'id' => $attribute->encodedId(),
            'name' => $this->getLocalisedName($attribute->name),
            'handle' => $attribute->handle,
            'position' => (string) $attribute->position,
            'filterable' => (bool) $attribute->filterable,
            'variant' => (bool) $attribute->variant,
            'searchable' => (bool) $attribute->searchable
        ];
    }

    public function includeGroup(Attribute $attribute)
    {
        return $this->item($attribute->group, new AttributeGroupTransformer);
    }
}
