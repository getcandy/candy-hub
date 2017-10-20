<?php

namespace GetCandy\Http\Transformers\Fractal\Associations;

use GetCandy\Api\Associations\Models\AssociationGroup;
use GetCandy\Http\Transformers\Fractal\Attributes\AttributeTransformer;
use GetCandy\Http\Transformers\Fractal\BaseTransformer;

class AssociationGroupTransformer extends BaseTransformer
{
    public function transform(AssociationGroup $group)
    {
        return [
            'name' => $group->name,
            'handle' => $group->handle
        ];
    }
}
