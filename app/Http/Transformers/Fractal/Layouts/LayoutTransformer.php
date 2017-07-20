<?php

namespace GetCandy\Http\Transformers\Fractal\Layouts;

use GetCandy\Api\Layouts\Models\Layout;
use GetCandy\Http\Transformers\Fractal\BaseTransformer;

class LayoutTransformer extends BaseTransformer
{
    protected $availableIncludes = [];

    public function transform(Layout $layout)
    {
        return [
            'id' => $layout->encodedId(),
            'name' => $layout->name,
            'handle' => $layout->handle
        ];
    }
}
