<?php

namespace GetCandy\Importers\Aqua\Models\Orders;

use GetCandy\Importers\Aqua\Models\BaseModel;
use GetCandy\Importers\Aqua\Decorator;
use Carbon\Carbon;

class Order extends BaseModel
{
    protected $table = 'cscart_orders';

    public function lines()
    {
        return $this->hasMany(OrderLine::class, 'order_id', 'order_id');
    }

    public function attributesToArray()
    {
        return [
            'id' => $this->id,
            'user_id' => null,
            'total' => $this->total,
            'vat' => $this->total - $this->subtotal - $this->shipping_cost,
            'shipping_total' => $this->shipping_cost,
            'status' => str_slug($this->status),
            'notes' => $this->notes,
            'currency' => 'GBP',
            'billing_phone' => $this->phone,
            'billing_firstname' => $this->b_lastname,
            'billing_lastname' => $this->b_firstname,
            'billing_address' => $this->b_address,
            'billing_address_two' => $this->b_address_2,
            'billing_city' => $this->b_city,
            'billing_county' => $this->b_county,
            'billing_state' => $this->b_state,
            'billing_country' => $this->b_country,
            'billing_zip' => $this->b_zipcode,
            'billing_phone' => $this->b_phone,
            'shipping_phone' => $this->phone,
            'shipping_firstname' => $this->s_lastname,
            'shipping_lastname' => $this->s_firstname,
            'shipping_address' => $this->s_address,
            'shipping_address_two' => $this->s_address_2,
            'shipping_city' => $this->s_city,
            'shipping_county' => $this->s_county,
            'shipping_state' => $this->s_state,
            'shipping_country' => $this->s_country,
            'shipping_zip' => $this->s_zipcode,
            'shipping_phone' => $this->s_phone,
            'contact_email' => $this->email,
            'contact_phone' => $this->phone
        ];
    }
}
