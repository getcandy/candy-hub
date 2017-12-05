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

    /**
     * Get a basket for a user
     *
     * @param User $user
     * 
     * @return Mixed
     */
    public function getForUser(User $user)
    {
        return $user->basket;
    }

    /**
     * Resolves a guest basket with an existing basket
     *
     * @param User $user
     * @param string $basketId
     * @param boolean $merge
     * 
     * @return Basket
     */
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
        return $basket;
    }

    /**
     * Merges two baskets
     *
     * @param Basket $guestBasket
     * @param Basket $userBasket
     * @return Basket
     */
    public function merge($guestBasket, $userBasket)
    {
        $newLines = $userBasket->lines->merge($guestBasket->lines);
        $guestBasket->update([
            'resolved_at' => Carbon::now(),
            'merged' => true
        ]);
        $userBasket->lines()->delete();
        $userBasket->lines()->createMany($newLines->toArray());
        return $userBasket;
    }
}
