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
        $order->shipping = 0;
        $order->vat = 0;

        $order->save();
        
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
        $order = $this->getByHashedId($id);
        
        $user = app('auth')->user();

        $order->save();
        
        // If this address doesn't exist, create it.
        if (!empty($data['address_id'])) {
            $shipping = app('api')->addresses()->getByHashedId($data['address_id']);
            $data = $shipping->toArray();
        } elseif ($user) {
            app('api')->addresses()->addShipping($user, $data);
        }

        $this->setFields($order, $data, 'shipping');
    
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
    public function setBilling($id, array $data)
    {
        $order = $this->getByHashedId($id);
        $user = app('auth')->user();

        $order->save();

        if (!empty($data['address_id'])) {
            $shipping = app('api')->addresses()->getByHashedId($data['address_id']);
            $data = $shipping->toArray();
        } elseif ($user) {
            app('api')->addresses()->addBilling($user, $data);
        }

        $this->setFields($order, $data, 'billing');

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
        $order->shipping = 0;
        $order->vat = 0;
        $order->currency = $basket->currency;

        $order->lines()->delete();

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
        $transaction = app('api')->payments()->charge(
            $data['payment_token'],
            $order->total
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
}
