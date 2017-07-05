<?php

namespace GetCandy\Api\Collections\Models;

use GetCandy\Api\Products\Models\Product;
use GetCandy\Api\Scaffold\BaseModel;
use GetCandy\Api\Traits\HasTranslations;
use GetCandy\Api\Traits\Attributable;

class Collection extends BaseModel
{
    use Attributable;

    protected $hashids = 'channel';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'attribute_data'
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
