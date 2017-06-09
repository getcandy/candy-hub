<?php

namespace GetCandy\Api\Collections\Models;

use GetCandy\Api\Scaffold\BaseModel;
use GetCandy\Api\Products\Models\Product;

class Collection extends BaseModel
{
    protected $hashids = 'channel';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
