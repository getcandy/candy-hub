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
        $values = [];
        $option_data = $this->product->option_data;
        foreach (json_decode($val, true) as $option) {
            $ref = explode('.', $option);
            $values[$ref[0]] = $option_data[$ref[0]]['options'][$ref[1]]['values'];
        }
        return $values;
    }
    public function setOptionsAttribute($val)
    {
        $this->attributes['options'] = json_encode($val);
    }
}
