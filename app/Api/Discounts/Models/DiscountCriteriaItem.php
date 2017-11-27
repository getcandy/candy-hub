<?php
namespace GetCandy\Api\Discounts\Models;

use GetCandy\Api\Scaffold\BaseModel;

class DiscountCriteriaItem extends BaseModel
{
    protected $fillable = ['modifier', 'target', 'source'];

    public function group()
    {
        return $this->belongsTo(DiscountCriteriaGroup::class);
    }
}
