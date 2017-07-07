<?php

namespace GetCandy\Http\Transformers\Fractal\Categories;

use GetCandy\Api\Categories\Models\Category;
use GetCandy\Http\Transformers\Fractal\BaseTransformer;
use GetCandy\Http\Transformers\Fractal\Routes\RouteTransformer;

class CategoryTransformer extends BaseTransformer
{

    protected $availableIncludes = [
        'routes'
    ];

    public function transform(Category $category)
    {
        $data = [
            'id' => $category->encodedId(),
            'attribute_data' => $category->attribute_data,
            'depth' => $category->depth
        ];

        $children = [];

        $i = 0;
        while ($category->children->count() > $i) {
            $transformer = $this->item($category->children->offsetGet($i), new CategoryTransformer);
            $children[] = app()->fractal->createData($transformer)->toArray();
            $i++;
        }

        $data['children'] = $children;

        return $data;
    }

    public function includeRoutes(Category $category)
    {
        return $this->collection($category->routes, new RouteTransformer);
    }
}
