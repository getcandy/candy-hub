<?php
namespace GetCandy\Http\Transformers\Fractal\Baskets;

use Carbon\Carbon;
use GetCandy\Api\Discounts\Factory;
use GetCandy\Api\Baskets\Models\Basket;
use GetCandy\Http\Transformers\Fractal\BaseTransformer;
use GetCandy\Http\Transformers\Fractal\Users\UserTransformer;
use GetCandy\Http\Transformers\Fractal\Discounts\DiscountTransformer;
use GetCandy\Http\Transformers\Fractal\Routes\RouteTransformer;
use TaxCalculator;

class BasketTransformer extends BaseTransformer
{
    protected $availableIncludes = [
        'lines', 'user', 'discounts', 'routes'
    ];

    public function transform(Basket $basket)
    {
        $basket = app('api')->baskets()->setTotals($basket);
        $data = [
            'id' => $basket->encodedId(),
            'total' => $basket->total,
            'tax_total' => round($basket->tax, 2)
        ];
        return $data;
    }

    protected function includeLines(Basket $basket)
    {
        return $this->collection($basket->lines, new BasketLineTransformer);
    }

    public function includeRoutes(Basket $basket)
    {
        return $this->collection($basket->routes, new RouteTransformer);
    }

    protected function includeUser(Basket $basket)
    {
        if (!$basket->user) {
            return null;
        }
        return $this->item($basket->user, new UserTransformer);
    }

    protected function includeDiscounts(Basket $basket)
    {
        return $this->collection($basket->discounts, new DiscountTransformer);
    }
}
