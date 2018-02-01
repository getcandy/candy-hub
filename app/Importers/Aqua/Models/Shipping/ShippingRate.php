<?php

namespace GetCandy\Importers\Aqua\Models\Shipping;

use GetCandy\Importers\Aqua\Models\BaseModel;
use GetCandy\Importers\Aqua\Models\UserGroups\UserGroup;

class ShippingRate extends BaseModel
{
    protected $table = 'cscart_shipping_rates';

    public function method()
    {
        return $this->belongsTo(ShippingMethod::class, 'shipping_id', 'shipping_id');
    }

    public function destination()
    {
        return $this->belongsTo(Destination::class, 'destination_id', 'destination_id');
    }

    public function attributesToArray()
    {
        return unserialize($this->rate_value);
    }
}
