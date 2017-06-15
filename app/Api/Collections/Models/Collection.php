<?php

namespace GetCandy\Api\Collections\Models;

use GetCandy\Api\Products\Models\Product;
use GetCandy\Api\Scaffold\BaseModel;
use GetCandy\Api\Traits\HasTranslations;

class Collection extends BaseModel
{
    use HasTranslations;

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
