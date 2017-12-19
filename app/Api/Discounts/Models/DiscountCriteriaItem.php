<?php
namespace GetCandy\Api\Discounts\Models;

use GetCandy\Api\Scaffold\BaseModel;

class DiscountCriteriaItem extends BaseModel
{
    protected $fillable = ['type', 'criteria'];

    protected $hashids = 'main';

    public function setCriteriaAttribute($val)
    {
        $this->attributes['criteria'] = json_encode($val);
    }

    public function set()
    {
        return $this->belongsTo(DiscountCriteriaSet::class, 'discount_criteria_set_id');
    }
}
