<?php
namespace GetCandy\Api\Shipping\Models;

use GetCandy\Api\Scaffold\BaseModel;

class ShippingPrice extends BaseModel
{
    /**
     * @var string
     */
    protected $hashids = 'main';

    protected $fillable = [
        'rate',
        'fixed',
        'min_weight',
        'weight_unit',
        'min_width',
        'width_unit',
        'min_height',
        'height_unit',
        'min_depth',
        'depth_unit',
        'volume_unit',
        'min_volume'
    ];

    public function method()
    {
        return $this->belongsTo(ShippingMethod::class, 'shipping_method_id');
    }
}
