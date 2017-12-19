<?php
namespace GetCandy\Api\Discounts\Models;

use GetCandy\Api\Scaffold\BaseModel;

class DiscountReward extends BaseModel
{
    protected $fillable = ['value', 'type'];

    protected $hashids = 'main';

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }
}
