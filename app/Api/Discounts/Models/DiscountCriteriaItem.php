<?php
namespace GetCandy\Api\Discounts\Models;

use GetCandy\Api\Scaffold\BaseModel;

class DiscountCriteriaItem extends BaseModel
{
    protected $fillable = ['operator', 'target', 'source', 'value'];

    public function set()
    {
        return $this->belongsTo(DiscountCriteriaSet::class, 'awawd');
    }
}
