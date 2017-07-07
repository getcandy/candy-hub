<?php

namespace GetCandy\Http\Transformers\Fractal\Routes;

use GetCandy\Api\Routes\Models\Route;
use GetCandy\Http\Transformers\Fractal\BaseTransformer;

class RouteTransformer extends BaseTransformer
{
    protected $availableIncludes = [
        'element'
    ];

    public function transform(Route $route)
    {
        return [
            'id' => $route->encodedId(),
            'default' => (bool) $route->default,
            'locale' => $route->locale,
            'slug' => $route->slug,
            'type' => str_slug(class_basename($route->element_type)),
        ];
    }

    public function includeElement(Route $route)
    {
        return $this->item($route->element, new ElementTransformer);
    }
}
