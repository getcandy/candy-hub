<?php
namespace GetCandy\Api\Baskets\Services;

use GetCandy\Api\Scaffold\BaseService;
use GetCandy\Api\Baskets\Models\Basket;
use GetCandy\Api\Auth\Models\User;
use Carbon\Carbon;
use GetCandy\Api\Baskets\Events\BasketStoredEvent;

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
     * Store a basket
     *
     * @param array $data
     * 
     * @return Basket
     */
    public function store(array $data, $user = null)
    {
        if (!empty($data['basket_id'])) {
            $basket = $this->getByHashedId($data['basket_id']);
        } elseif ($user && $user->basket) {
            $basket = $user->basket;
        } else {
            $basket = new $this->model;
        }

        if ($user) {
            $basket->user()->associate($user);
        }

        if (empty($data['currency'])) {
            $basket->currency = app('api')->currencies()->getDefaultRecord()->code;
        } else {
            $basket->currency = $data['currency'];
        }

        $basket->save();

        $variants = collect($data['variants'])->map(function ($item) {
            return [
                'product_variant_id' => app('api')->productVariants()->getDecodedId($item['id']),
                'quantity' => $item['quantity'],
                'total' => $item ['price']
            ];
        });

        $basket->lines()->delete();
        $basket->lines()->createMany($variants->toArray());

        event(new BasketStoredEvent($basket));

        return $basket;
    }

    public function addDiscount($basketId, $coupon)
    {
        $basket = $this->getByHashedId($basketId);
        $coupon = app('api')->discounts()->getByCoupon($coupon);
        dd($coupon);
        $basket->discounts()->attach($coupon->discount, ['coupon' => $coupon]);
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
    
        if ($merge) {
            $userBasket->merged = true;
            return $this->merge($basket, $userBasket);
        }
        $basket->resolved_at = Carbon::now();
        $user->basket()->save($basket);
        $basket->save();
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
        $newLines = $guestBasket->lines;
        $overrides = $newLines->pluck('variant.id');
        $oldLines = $userBasket->lines->filter(function ($line) use ($overrides) {
            if (!$overrides->contains($line->variant->id)) {
                return $line;
            }
        });
        $guestBasket->update([
            'resolved_at' => Carbon::now(),
            'merged_id' => $userBasket->id
        ]);
        $userBasket->lines()->delete();
        $userBasket->lines()->createMany(
            $newLines->merge($oldLines)->toArray()
        );
        return $userBasket;
    }
}
