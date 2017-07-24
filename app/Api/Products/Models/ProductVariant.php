<?php

namespace GetCandy\Api\Products\Models;

use GetCandy\Api\Attributes\Models\Attribute;
use GetCandy\Api\Scaffold\BaseModel;
use GetCandy\Api\Traits\HasAttributes;

class ProductVariant extends BaseModel
{
    use HasAttributes;
    /**
     * The Hashid Channel for encoding the id
     * @var string
     */
    protected $hashids = 'product';

    protected $fillable = ['options', 'price', 'sku', 'stock'];


    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getOptionsAttribute($val)
    {
        return json_decode($val, true);
    }
    public function setOptionsAttribute($val)
    {
        $this->attributes['options'] = json_encode($val);
    }
}
