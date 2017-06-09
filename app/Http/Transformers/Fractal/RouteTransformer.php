<?php

namespace GetCandy\Http\Transformers\Fractal;

use GetCandy\Api\Routes\Models\Route;
use League\Fractal\TransformerAbstract;

class RouteTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'element'
    ];

    public function transform(Route $route)
    {
        return [
            'id' => $route->encodedId(),
            'slug' => $route->slug
        ];
    }

    public function includeElement(Route $route)
    {
        return $this->item($route->element, new $route->element->transformer);
    }
}
