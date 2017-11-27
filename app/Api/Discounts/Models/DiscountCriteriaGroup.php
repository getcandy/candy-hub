<?php

namespace GetCandy\Api\Discounts\Models;

use GetCandy\Api\Scaffold\BaseModel;

class DiscountCriteriaGroup extends BaseModel
{
    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }

    public function items()
    {
        return $this->hasMany(DiscountCriteriaItem::class);
    }
}
