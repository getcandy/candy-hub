<?php

namespace GetCandy\Api\Products\Models;

use GetCandy\Api\Attributes\Models\Attribute;
use GetCandy\Api\Scaffold\BaseModel;

class ProductVariant extends BaseModel
{
    /**
     * The Hashid Channel for encoding the id
     * @var string
     */
    protected $hashids = 'product';

    protected $fillable = ['name'];


    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
