<?php

namespace GetCandy\Api\Products\Models;

use GetCandy\Api\Attributes\Models\Attribute;
use GetCandy\Api\Scaffold\BaseModel;
use GetCandy\Api\Traits\HasTranslations;

class ProductFamily extends BaseModel
{
    use HasTranslations;

    /**
     * The Hashid Channel for encoding the id
     * @var string
     */
    protected $hashids = 'product_family';

    protected $fillable = ['name'];


    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get the attributes associated to the product family
     * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function attributes()
    {
        return $this->belongsToMany(Attribute::class);
    }
}
