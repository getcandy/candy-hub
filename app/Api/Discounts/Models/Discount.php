<?php

namespace GetCandy\Api\Discounts\Models;

use GetCandy\Api\Scaffold\BaseModel;

class Discount extends BaseModel
{
    public function sets()
    {
        return $this->hasMany(DiscountCriteriaSet::class);
    }
}
