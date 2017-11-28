<?php

namespace GetCandy\Api\Discounts\Models;

use GetCandy\Api\Scaffold\BaseModel;
use GetCandy\Api\Traits\HasAttributes;

class Discount extends BaseModel
{
    use HasAttributes;

    public function sets()
    {
        return $this->hasMany(DiscountCriteriaSet::class);
    }

    public function rewards()
    {
        return $this->hasMany(DiscountReward::class);
    }
}
