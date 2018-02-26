<?php

namespace GetCandy\Importers\Aqua\Models\Orders;

use GetCandy\Importers\Aqua\Models\BaseModel;
use GetCandy\Importers\Aqua\Decorator;
use Carbon\Carbon;

class OrderLine extends BaseModel
{
    protected $table = 'cscart_order_details';

    public function attributesToArray()
    {
        $extra = unserialize($this->extra);
        
        return [
            'order_id' => $this->order_id,
            'quantity' => $this->amount,
            'total' => $this->amount * $this->price,
            'product' => $extra['product'],
            'sku' => $this->product_code,
            'variant' => $extra['product']
        ];
    }
}
