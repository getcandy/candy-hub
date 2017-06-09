<?php

namespace GetCandy\Http\Transformers\Fractal;

use GetCandy\Api\Pages\Models\Page;
use GetCandy\Http\Transformers\Fractal\AttributeGroupTransformer;
use League\Fractal\TransformerAbstract;

class PageTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'element'
    ];

    public function transform(Page $page)
    {
        return [
            'id' => $page->encodedId(),
            'slug' => $page->slug,
            'seo_title' => $page->seo_title,
            'seo_description' => $page->seo_description
        ];
    }

    public function includeElement(Page $page)
    {
        return $this->item($page->element, new $page->element->transformer);
    }
}
