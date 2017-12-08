<?php
namespace GetCandy\Api\Shipping\Models;

use GetCandy\Api\Scaffold\BaseModel;

class ShippingZone extends BaseModel
{
    /**
     * @var string
     */
    protected $hashids = 'main';

    protected $fillable = [
        'name'
    ];

    protected function method()
    {
        return $this->belongsTo(ShippingMethod::class);
    }
}
