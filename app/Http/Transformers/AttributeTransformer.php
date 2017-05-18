<?php

namespace GetCandy\Http\Transformers;

use GetCandy\Api\Attributes\Models\Attribute;
use League\Fractal\TransformerAbstract;
use GetCandy\Http\Transformers\AttributeGroupTransformer;

class AttributeTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'group'
    ];

    public function transform(Attribute $attribute)
    {
        return [
            'id' => $attribute->encodedId(),
            'name' => $attribute->name,
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
