<?php

namespace GetCandy\Api\Orders\Services;

use TaxCalculator;
use GetCandy\Api\Factory;
use GetCandy\Api\Auth\Models\User;
use GetCandy\Api\Orders\Models\Order;
use GetCandy\Api\Scaffold\BaseService;
use GetCandy\Api\Baskets\Models\Basket;
use GetCandy\Api\Orders\Events\OrderBeforeSavedEvent;
use GetCandy\Api\Orders\Events\OrderProcessedEvent;
use GetCandy\Api\Orders\Exceptions\IncompleteOrderException;
use Carbon\Carbon;

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

    /**
     * Stores an order
     *
     * @param string $basketId
     *
     * @return Order
     */
    public function store($basketId, $user = null)
    {
        // // Get the basket
        $basket = app('api')->baskets()->getByHashedId($basketId);

        app('api')->baskets()->setTotals($basket);

        if ($basket->order) {
            $order = $basket->order;
        } else {
            $order = new Order;
            $order->basket()->associate($basket);
        }

        if ($user) {
            $order->user()->associate($user);
            foreach ($user->addresses as $address) {
                $this->setFields($order, $address->fields, $address->billing ? 'billing' : 'shipping');
            }
        }

        $order->total = $basket->total;

        if ($order->shipping_total) {
            $order->total += $order->shipping_total;
        } else {
            $order->shipping_total = 0;
        }

        $order->currency = $basket->currency;

        $order->vat = $basket->tax;
var_dump($order);exit;
        $order->save();

        $order->discounts()->delete();
        $order->lines()->delete();

        $order->discounts()->createMany(
            $this->mapOrderDiscounts($basket)
        );

        $order->lines()->createMany(
            $this->mapOrderLines($basket)
        );

        return $order;
    }

    /**
     * Update an order
     *
     * @param string $orderId
     * @param array $data
     *
     * @return Order
     */
    public function update($orderId, array $data)
    {
        $order = $this->getByHashedId($orderId);

        if (!empty($data['tracking_no'])) {
            $order->tracking_no = $data['tracking_no'];
        }

        if (!empty($data['status'])) {
            $order->status = $data['status'];
        }

        if (strtolower($order->status) == 'dispatched') {
            $order->dispatched_at = Carbon::now();
        }

        event(new OrderBeforeSavedEvent($order));
        $order->save();

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
    public function setShipping($id, array $data, $user = null)
    {
        return $this->addAddress(
            $id,
            $data,
            'shipping',
            $user
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
    public function setBilling($id, array $data, $user = null)
    {
        return $this->addAddress(
            $id,
            $data,
            'billing',
            $user
        );
    }

    /**
     * Adds an address for an order
     *
     * @param string $id
     * @param array $data
     * @param string $type
     *
     * @return Order
     */
    protected function addAddress($id, $data, $type, $user = null)
    {
        $order = $this->getByHashedId($id);

        if (!empty($data['vat_no'])) {
            $order->vat_no = $data['vat_no'];
            unset($data['vat_no']);
        }

        $order->save();

        // If this address doesn't exist, create it.
        if (!empty($data['address_id'])) {
            $shipping = app('api')->addresses()->getByHashedId($data['address_id']);
            $data = $shipping->toArray();
        } elseif ($user) {
            $address = app('api')->addresses()->addAddress($user, $data, $type);
            $data = $address->fields;
        }

        if ($user) {
            $order->shipping_phone = $user->contact_number;
            $order->billing_phone = $user->contact_number;
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

        // Remove old shipping beforehand
        if ($order->shipping_total) {
            $order->total -= $order->shipping_total;
        }

        $order->shipping_total = $price->rate;
        $order->shipping_method = $price->method->attribute('name');
        $order->total = $order->total + $price->rate;

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
            $order->setAttribute($field, $value);
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
        $order->vat = $basket->vat_total;
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
                'total' => $line->current_total,
                'quantity' => $line->quantity,
                'product' => $line->variant->product->attribute('name'),
                'variant' => $line->variant->name
            ]);
        }

        return $lines;
    }

    /**
     * Maps an orders discounts from a basket
     *
     * @param Basket $basket
     *
     * @return array
     */
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
        return (bool) $this->model->where('id', '=', $realId)->where('status', '=', 'awaiting-payment')->exists();
    }

    /**
     * Checks whether an order is processable
     *
     * @param Order $order
     *
     * @return boolean
     */
    protected function isProcessable(Order $order)
    {
        $fields = $order->required->filter(function ($field) use ($order) {
            return $order->getAttribute($field);
        });
        return $fields->count() === $order->required->count();
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

        if (!$this->isProcessable($order)) {
            throw new IncompleteOrderException;
        }

        if (!empty($data['notes'])) {
            $order->notes = $data['notes'];
        }

        $total = $order->total + $order->shipping_total;

        $transaction = app('api')->payments()->charge(
            $data['payment_token'],
            $order
        );

        if ($transaction->success) {
            $order->status = 'complete';
        }

        $order->save();

        event(new OrderProcessedEvent($order));

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

        // Take off any previous shipping costs
        if ($order->shipping_total) {
            $order->total -= $order->shipping_total;
        }

        $order->shipping_total = round($price->rate, 2);
        $order->shipping_method = $price->method->attribute('name');
        $order->total += round($price->rate, 2);

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

        if (!empty($data['email'])) {
            $order->contact_email = $data['email'];
        }

        if (!empty($data['phone'])) {
            $order->contact_phone = $data['phone'];
        }

        $order->save();
        return $order;
    }
}
