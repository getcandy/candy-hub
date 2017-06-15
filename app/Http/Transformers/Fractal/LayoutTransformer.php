<?php

namespace GetCandy\Http\Transformers\Fractal;

use GetCandy\Api\Layouts\Models\Layout;

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
