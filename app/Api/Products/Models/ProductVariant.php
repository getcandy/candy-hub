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
        foreach (json_decode($val, true) as $option => $value) {
            if (! empty($data = $option_data[$option])) {
                $values[$option] = $data['options'][$value]['values'];
            }
        }
        return $values;
    }
    public function setOptionsAttribute($val)
    {
        $options = [];
        foreach ($val as $option => $value) {
            if (!empty($value['en'])) {
                $value = $value['en'];
            }
            $options[str_slug($option)] = str_slug($value);
        }
        $this->attributes['options'] = json_encode($options);
    }
}
