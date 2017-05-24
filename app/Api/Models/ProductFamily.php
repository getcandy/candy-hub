<?php

namespace GetCandy\Api\Models;

use GetCandy\Api\Models\Attribute;
use Illuminate\Database\Eloquent\Model;

class ProductFamily extends Model
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
