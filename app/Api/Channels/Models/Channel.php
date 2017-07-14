<?php

namespace GetCandy\Api\Channels\Models;

use GetCandy\Api\Scaffold\BaseModel;
use GetCandy\Api\Products\Models\Product;

class Channel extends BaseModel
{
    protected $hashids = 'channel';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'handle',
        'default'
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('visible', 'published_at');
    }
}
