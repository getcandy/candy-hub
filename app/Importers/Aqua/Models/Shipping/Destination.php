<?php

namespace GetCandy\Importers\Aqua\Models\Shipping;

use GetCandy\Importers\Aqua\Models\BaseModel;
use GetCandy\Importers\Aqua\Models\UserGroups\UserGroup;

class Destination extends BaseModel
{
    protected $table = 'cscart_destinations';

    public function method()
    {
        return $this->belongsTo(ShippingMethod::class, 'shipping_id', 'shipping_id');
    }
}
