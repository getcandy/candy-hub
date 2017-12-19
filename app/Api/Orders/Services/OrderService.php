<?php

namespace GetCandy\Api\Orders\Services;

use GetCandy\Api\Baskets\Models\Basket;
use GetCandy\Api\Factory;
use GetCandy\Api\Orders\Models\Order;
use GetCandy\Api\Scaffold\BaseService;
use GetCandy\Api\Auth\Models\User;

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

    public function store($basketId)
    {
        // // Get the basket
        $basket = app('api')->baskets()->getByHashedId($basketId);

        $order = new Order;
        $order->basket()->associate($basket);
        $order->user()->associate(app('auth')->user());
        $order->total = $basket->total;
        $order->currency = $basket->currency;
        $order->shipping_total = 0;
        $order->vat = 0;

        $order->save();

        $order->discounts()->createMany(
            $this->mapOrderDiscounts($basket)
        );
        
        $order->lines()->createMany(
            $this->mapOrderLines($basket)
        );

        return $order;
    }

    /**
     * Set the delivery details
     *
     * @param string $id
     * @param array $data
     * 
     * @return Order
     */
    public function setShipping($id, array $data)
    {
        return $this->addAddress(
            $id,
            $data,
            'shipping'
        );
    }

     /**
     * Set the delivery details
     *
     * @param string $id
     * @param array $data
     *
     * @return Order
     */
    public function setBilling($id, array $data)
    {
        return $this->addAddress(
            $id,
            $data,
            'billing'
        );
    }

    /**
     * Adds an address for a
     *
     * @param string $id
     * @param array $data
     * @param string $type
     * 
     * @return Order
     */
    protected function addAddress($id, $data, $type)
    {
        $order = $this->getByHashedId($id);
        
        $user = app('auth')->user();

        $order->save();
        
        // If this address doesn't exist, create it.
        if (!empty($data['address_id'])) {
            $shipping = app('api')->addresses()->getByHashedId($data['address_id']);
            $data = $shipping->toArray();
        } elseif ($user) {
            app('api')->addresses()->addAddress($user, $data, $type);
        }

        $this->setFields($order, $data, $type);


        $order->save();

        return $order;
    }
    
    /**
     * Sets the delivery price on an
     *
     * @param string $orderId
     * @param string $priceId
     * 
     * @return Order
     */
    public function setDeliveryPrice($orderId, $priceId)
    {
        $price = app('api')->shippingPrices()->getByHashedId($priceId);
        $order = app('api')->orders()->getByHashedId($orderId);

        $order->shipping_total = $price->rate;
        $order->shipping_method = $price->method->attribute('name');

        $order->save();

        return $order;
    }

    /**
     * Sets the fields for contact info on the order
     *
     * @param string $order
     * @param array $fields
     * @param string $prefix
     * 
     * @return void
     */
    protected function setFields($order, array $fields, $prefix)
    {
        foreach ($fields as $handle => $value) {
            $field = $prefix . '_' . $handle;
            if (array_key_exists($field, $order->getAttributes())) {
                $order->setAttribute($field, $value);
            }
        }
    }

    /**
     * Expires an order
     *
     * @param string $orderId
     *
     * @return void
     */
    public function expire($orderId)
    {
        $order = $this->getByHashedId($orderId);

        $order->status = 'expired';
        $order->save();

        return true;
    }

    public function getByHashedId($id)
    {
        $id = $this->model->decodeId($id);

        $query = $this->model->withoutGlobalScope('open')->withoutGlobalScope('not_expired');
        return $query->findOrFail($id);
    }

    /**
     * Syncs a given basket with its order
     *
     * @param Order $order
     * @param Basket $basket
     * 
     * @return Order
     */
    public function syncWithBasket(Order $order, Basket $basket)
    {
        $order->total = $basket->total;
        $order->vat = 0;
        $order->currency = $basket->currency;

        $order->lines()->delete();
        $order->discounts()->delete();

        $order->discounts()->createMany(
            $this->mapOrderDiscounts($basket)
        );

        $order->lines()->createMany(
            $this->mapOrderLines($basket)
        );

        $order->save();

        return $order;
    }

    /**
     * Maps the order lines from a basket
     *
     * @param Basket $basket
     * 
     * @return void
     */
    protected function mapOrderLines($basket)
    {
        $lines = [];

        foreach ($basket->lines as $line) {
            array_push($lines, [
                'sku' => $line->variant->sku,
                'total' => $line->total,
                'quantity' => $line->quantity,
                'product' => $line->variant->product->attribute('name'),
                'variant' => $line->variant->name
            ]);
        }

        return $lines;
    }

    protected function mapOrderDiscounts($basket)
    {
        $discounts = [];

        foreach ($basket->discounts as $discount) {
            $amount = 0;

            foreach ($discount->rewards as $reward) {
                array_push($discounts, [
                    'coupon' => $discount->pivot->coupon,
                    'name' => $discount->attribute('name'),
                    'description' => $discount->attribute('description'),
                    'type' => $reward->type,
                    'amount' => $reward->value
                ]);
            }
        }

        return $discounts;
    }

    /**
     * Determines whether an active order exists with this id
     *
     * @param string $orderId
     *
     * @return boolean
     */
    public function isActive($orderId)
    {
        $realId = $this->getDecodedId($orderId);
        return (bool) $this->model->where('id', '=', $realId)->where('status', '=', 'open')->exists();
    }

    /**
     * Process an order for payment
     *
     * @param array $data
     * @return mixed
     */
    public function process(array $data)
    {
        $order = $this->getByHashedId($data['order_id']);

        $total = $order->total + $order->shipping_total;

        $transaction = app('api')->payments()->charge(
            $data['payment_token'],
            $total
        );

        if ($transaction->success) {
            $order->status = 'complete';
            $order->save();
        }

        $transaction->order_id = $order->id;
        $transaction->save();
        return $transaction;
    }

    /**
     * Get paginated orders
     *
     * @param integer $length
     * @param int $page
     * @param User $user
     * @return void
     */
    public function getPaginatedData($length = 50, $page = 1, $user = null)
    {
        $query = $this->model->withoutGlobalScope('open')->withoutGlobalScope('not_expired');
        if (!app('auth')->user()->hasRole('admin')) {
            $query = $query->whereHas('user', function ($q) use ($user) {
                $q->whereId($user->id);
            });
        }
        return $query->paginate($length, ['*'], 'page', $page);
    }

    /**
     * Set the shipping cost and method on an order
     *
     * @param string $orderId
     * @param string $priceId
     * 
     * @return Order
     */
    public function setShippingCost($orderId, $priceId)
    {
        $order = $this->getByHashedId($orderId);
        $price = app('api')->shippingPrices()->getByHashedId($priceId);

        $order->shipping_total = $price->rate;
        $order->shipping_method = $price->method->attribute('name');
        $order->save();

        return $order;
    }

    /**
     * Set the contact details on an order
     *
     * @param string $orderId
     * @param array $data
     * 
     * @return Order
     */
    public function setContact($orderId, array $data)
    {
        $order = $this->getByHashedId($orderId);
        
        if (!empty($data['contact_email'])) {
            $order->contact_email = $data['contact_email'];
        }

        if (!empty($data['contact_phone'])) {
            $order->contact_phone = $data['contact_phone'];
        }

        $order->save();
        return $order;
    }
}
