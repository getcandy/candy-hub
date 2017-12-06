<?php
namespace GetCandy\Api\Payments\Models;

use GetCandy\Api\Scaffold\BaseModel;
use GetCandy\Api\Orders\Models\Order;

class Transaction extends BaseModel
{
    protected $hashids = 'order';

    protected $fillable = [
        'merchant', 'success', 'status'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
