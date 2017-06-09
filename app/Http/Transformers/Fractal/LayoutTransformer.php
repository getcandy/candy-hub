<?php

namespace GetCandy\Http\Transformers\Fractal;

use GetCandy\Api\Layouts\Models\Layout;
use League\Fractal\TransformerAbstract;

class LayoutTransformer extends TransformerAbstract
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
