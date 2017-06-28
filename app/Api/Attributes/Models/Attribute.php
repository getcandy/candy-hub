<?php

namespace GetCandy\Api\Attributes\Models;

use GetCandy\Api\Products\Models\Product;
use GetCandy\Api\Scaffold\BaseModel;
use GetCandy\Api\Traits\HasTranslations;

class Attribute extends BaseModel
{
    use HasTranslations;

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

    /**
     * Sets the name attribute to a json string
     * @param array $value
     */
    public function setLookupsAttribute(array $value)
    {
        if (is_array($value)) {
            $this->attributes['lookups'] = json_encode($value);
        } else {
            $this->attributes['lookups'] = $value;
        }
    }

    public function getLookupsAttribute($value)
    {
        return json_decode($value, true);
    }
}
