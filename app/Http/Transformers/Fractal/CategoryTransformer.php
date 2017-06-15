<?php

namespace GetCandy\Http\Transformers\Fractal;

use GetCandy\Api\Categories\Models\Category;

class CategoryTransformer extends BaseTransformer
{

    public function transform(Category $category)
    {
        $data = [
            'id' => $category->encodedId(),
            'name' => $this->getLocalisedName($category->name),
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
}
