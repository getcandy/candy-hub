<?php
namespace GetCandy\Api\Baskets\Models;

use GetCandy\Api\Products\Models\ProductVariant;
use GetCandy\Api\Scaffold\BaseModel;

class BasketLine extends BaseModel
{
    protected $hashids = 'basket';

    protected $fillable = ['quantity', 'product_variant_id', 'price'];

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
}
