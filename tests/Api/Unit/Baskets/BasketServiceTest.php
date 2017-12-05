<?php
namespace Tests;

use GetCandy\Api\Products\Models\ProductVariant;
use GetCandy\Api\Baskets\Models\Basket;
use Auth;

/**
 * @group services
 */
class BasketServiceTest extends TestCase
{
    public function testResolveWithoutMerge()
    {
        // Create a basket as a guest
        $guestBasket = $this->createGuestBasket();
        $this->assertTrue($guestBasket instanceof Basket);
        $this->assertNull($guestBasket->user);

        dump('Guest: ' . $guestBasket->encodedId());

        // Create a basket as a user
        $user = \Auth::loginUsingId(1);
        $userBasket = $this->createUserBasket();
        $this->assertTrue($userBasket instanceof Basket);
        $this->assertTrue($user->id == $userBasket->user->id);

        dump('User: ' . $userBasket->encodedId());

        $basket = app('api')->baskets()->resolve($user, $guestBasket->encodedId());

        echo '---';
        dump($user->basket->encodedId(), $guestBasket->encodedId());
        
        $this->assertTrue($user->basket->encodedId() == $guestBasket->encodedId());
    }

    protected function createGuestBasket()
    {
        $lines = ProductVariant::take(2)->get()->map(function ($item) {
            return [
                'id' => $item->encodedId(),
                'price' => $item->price,
                'quantity' => 1
            ];
        });
        return app('api')->baskets()->create([
            'variants' => $lines->toArray()
        ]);
    }

    protected function createUserBasket()
    {
        $lines = ProductVariant::take(2)->get()->map(function ($item) {
            return [
                'id' => $item->encodedId(),
                'price' => $item->price,
                'quantity' => 2
            ];
        });
        return app('api')->baskets()->create([
            'variants' => $lines->toArray()
        ], Auth::user());
    }
}
