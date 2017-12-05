<?php

namespace GetCandy\Api\Orders\Services;

use GetCandy\Api\Scaffold\BaseService;
use GetCandy\Api\Orders\Models\Order;

class OrderService extends BaseService
{
    /**
     * @var Basket
     */
    protected $model;

    public function __construct()
    {
        $this->model = new Order();
    }

    public function create($basketId)
    {
        // Get the basket
        $basket = app('api')->baskets()->getByHashedId($basketId);

        $order = new Order;

        $order->lines()->createMany($basket->lines->toArray());
    }
}
