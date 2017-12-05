<?php
namespace GetCandy\Http\Transformers\Fractal\Baskets;

use GetCandy\Http\Transformers\Fractal\BaseTransformer;
use GetCandy\Api\Baskets\Models\Basket;
use Carbon\Carbon;
use GetCandy\Http\Transformers\Fractal\Users\UserTransformer;

class BasketTransformer extends BaseTransformer
{

    protected $availableIncludes = [
        'lines', 'user'
    ];

    public function transform(Basket $basket)
    {
        $data = [
            'id' => $basket->encodedId()
        ];
        return $data;
    }

    protected function includeLines(Basket $basket)
    {
        return $this->collection($basket->lines, new BasketLineTransformer);
    }

    protected function includeUser(Basket $basket)
    {
        if (!$basket->user) {
            return null;
        }
        return $this->item($basket->user, new UserTransformer);
    }
}
