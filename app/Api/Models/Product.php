<?php

namespace GetCandy\Api\Models;

use GetCandy\Api\Models\Attribute;
use GetCandy\Api\Models\ProductFamily;
use GetCandy\Api\Traits\Indexable;

class Product extends BaseModel
{
    use Indexable;

    protected $hashids = 'product';

    protected $index = 'products';

    protected $mapping = [
        'name' => [
            'type' => 'string',
            'analyzer' => 'standard',
            'fields' => [
                'english' => [
                    'type' => 'string',
                    'analyzer' => 'english'
                ]
            ]
        ]
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'price'
    ];

    /**
     * Get the attributes associated to the product
     * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function attributes()
    {
        return $this->belongsToMany(Attribute::class)->withTimestamps();
    }

    /**
     * Get the related family
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function family()
    {
        return $this->belongsTo(ProductFamily::class, 'product_family_id');
    }

    public function getMappingAttribute()
    {
        return $this->mapping;
    }

    public function getIndexAttribute()
    {
        return $this->index;
    }
}
