<?php

namespace GetCandy\Api\Products\Models;

use GetCandy\Api\Attributes\Models\Attribute;
use GetCandy\Api\Scaffold\BaseModel;
use GetCandy\Api\Traits\Attributable;

class ProductVariant extends BaseModel
{
    use Attributable;
    /**
     * The Hashid Channel for encoding the id
     * @var string
     */
    protected $hashids = 'product';

    protected $fillable = ['attribute_data', 'sku'];


    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
