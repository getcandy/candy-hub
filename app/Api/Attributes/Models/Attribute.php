<?php

namespace GetCandy\Api\Attributes\Models;

use GetCandy\Api\Products\Models\Product;
use GetCandy\Api\Traits\Hashids;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use Hashids;

    protected $hashids = 'attribute';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'handle',
        'position',
        'variant',
        'searchable',
        'filterable'
    ];

    public function group()
    {
        return $this->belongsTo(AttributeGroup::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)->withTimestamps();
    }
}
