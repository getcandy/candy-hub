<?php

namespace GetCandy\Http\Transformers\Fractal;

use GetCandy\Api\Routes\Models\Route;

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
        return $this->item($route->element, new $route->element->transformer);
    }
}
