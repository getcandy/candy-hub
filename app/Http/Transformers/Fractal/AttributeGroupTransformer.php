<?php

namespace GetCandy\Http\Transformers\Fractal;

use GetCandy\Api\Attributes\Models\AttributeGroup;
use League\Fractal\TransformerAbstract;

class AttributeGroupTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'attributes'
    ];

    public function transform(AttributeGroup $group)
    {
        return [
            'id' => $group->encodedId(),
            'name' => $group->name,
            'handle' => $group->handle,
            'position' => (string) $group->position
        ];
    }

    public function includeAttributes(AttributeGroup $group)
    {
        return $this->collection($group->attributes, new AttributeTransformer);
    }
}
