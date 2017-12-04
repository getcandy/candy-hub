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
    
        $line = $basket->lines()->create([
            'quantity' => $data['quantity'],
            'price' => $data['price'],
            'product_variant_id' => app('api')->productVariants()->getDecodedId($data['variant'])
        ]);
        return $basket;
    }

    public function update($id, array $data)
    {
        $basket = $this->getByHashedId($id);

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
