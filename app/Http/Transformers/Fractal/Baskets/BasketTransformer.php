<?php
namespace GetCandy\Http\Transformers\Fractal\Baskets;

use GetCandy\Http\Transformers\Fractal\BaseTransformer;
use GetCandy\Api\Baskets\Models\Basket;
use Carbon\Carbon;

class BasketTransformer extends BaseTransformer
{

    protected $availableIncludes = [
        'lines'
    ];

    public function transform(Basket $basket)
    {
        $data = [
            'id' => $basket->encodedId(),
            'abandoned_at' => $basket->abandoned_at ? Carbon::parse($basket->abandoned_at)->toIso8601String() : null
        ];
        return $data;
    }

    protected function includeLines(Basket $basket)
    {
        return $this->collection($basket->lines, new BasketLineTransformer);
    }
}
