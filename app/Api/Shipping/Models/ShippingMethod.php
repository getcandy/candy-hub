<?php
namespace GetCandy\Api\Shipping\Models;

use GetCandy\Api\Scaffold\BaseModel;
use GetCandy\Api\Traits\HasAttributes;

class ShippingMethod extends BaseModel
{
    use HasAttributes;

    /**
     * @var string
     */
    protected $hashids = 'main';

    protected $fillable = [
        'attribute_data',
        'type'
    ];

    protected function zones()
    {
        return $this->belongsToMany(ShippingZone::class, 'shipping_method_zones');
    }

    protected function prices()
    {
        return $this->hasMany(ShippingPrice::class);
    }
}
