<?php
namespace GetCandy\Api\Baskets\Services;

use GetCandy\Api\Scaffold\BaseService;
use GetCandy\Api\Baskets\Models\Basket;

class BasketService extends BaseService
{
    /**
     * @var Basket
     */
    protected $model;

    public function __construct()
    {
        $this->model = new Basket();
    }

    /**
     * Create a basket
     *
     * @param array $data
     * 
     * @return Basket
     */
    public function create(array $data)
    {
        if (!empty($data['basket_id'])) {
            $basket = $this->getByHashedId($data['basket_id']);
        } else {
            $basket = new $this->model;
            $basket->save();
        }

        $variants = collect($data['variants'])->map(function ($item) {
            return [
                'product_variant_id' => app('api')->productVariants()->getDecodedId($item['id']),
                'quantity' => $item['quantity'],
                'price' => $item['price']
            ];
        });

        $basket->lines()->delete();
        $basket->lines()->createMany($variants->toArray());

        return $basket;
    }
}
