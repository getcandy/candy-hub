<?php

namespace GetCandy\Api\Models;

use GetCandy\Api\Models\Attribute;

class ProductFamily extends BaseModel
{
    protected $hashids = 'product_family';

    public function product()
    {
        return $this->hasMany(Product::class);
    }
    public function attributes()
    {
        return $this->belongsToMany(Attribute::class);
    }
}
