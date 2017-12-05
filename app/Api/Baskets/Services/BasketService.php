<?php
namespace GetCandy\Api\Baskets\Services;

use GetCandy\Api\Scaffold\BaseService;
use GetCandy\Api\Baskets\Models\Basket;
use GetCandy\Api\Auth\Models\User;
use Carbon\Carbon;

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
    public function create(array $data, $user = null)
    {
        if (!empty($data['basket_id'])) {
            $basket = $this->getByHashedId($data['basket_id']);
        } else {
            $basket = new $this->model;
        }

        if ($user) {
            $basket->user()->associate($user);
        }

        $basket->save();

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

    public function getForUser(User $user)
    {
        return $user->basket;
    }

    public function resolve($user, $basketId, $merge = false)
    {
        // Guest basket
        $basket = $this->getByHashedId($basketId);
        // User basket
        $userBasket = $user->basket;

        $userBasket->resolved_at = Carbon::now();
    
        if ($merge) {
            $userBasket->merged = true;
            return $this->merge($basket, $userBasket);
        }

        $user->basket()->save($basket);
        $userBasket->save();
        dd($user);

        return $basket;
    }

    public function merge($first, $second)
    {
        dd('hi');
    }
}
