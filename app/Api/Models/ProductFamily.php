<?php

namespace GetCandy\Api\Models;

use GetCandy\Api\Models\Attribute;

class ProductFamily extends BaseModel
{
    protected $hashids = 'product_family';

    protected $fillable = ['name'];

    /**
     * Get the products associated to the product family
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
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
