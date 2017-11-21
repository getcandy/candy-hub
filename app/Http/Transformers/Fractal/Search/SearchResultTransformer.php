<?php

namespace GetCandy\Http\Transformers\Fractal\Search;

use GetCandy\Api\Routes\Models\Route;
use GetCandy\Http\Transformers\Fractal\BaseTransformer;
use Illuminate\Database\Eloquent\Model;
use GetCandy\Http\Transformers\Fractal\ProductTransformer;

class SearchResultTransformer extends BaseTransformer
{
    protected $types = [
        'product' => ProductTransformer::class
    ];

    public function transform($result)
    {
        dd($result);
    }
}
