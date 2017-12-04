<?php
namespace GetCandy\Api\Baskets\Models;

use GetCandy\Api\Scaffold\BaseModel;

class Basket extends BaseModel
{
    protected $hashids = 'basket';

    protected $fillable = [
        'quantity'
    ];

    public function lines()
    {
        return $this->hasMany(BasketLine::class);
    }
}
