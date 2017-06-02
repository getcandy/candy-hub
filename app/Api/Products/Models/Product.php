<?php

namespace GetCandy\Api\Products\Models;

use GetCandy\Api\Attributes\Models\Attribute;
use GetCandy\Api\Scaffold\BaseModel;
use GetCandy\Api\Traits\Indexable;
use GetCandy\Api\Pages\Models\Page;
use GetCandy\Http\Transformers\Fractal\ProductTransformer;

class Product extends BaseModel
{
    use Indexable;

    public $transformer = ProductTransformer::class;

    /**
     * The Hashid Channel for encoding the id
     * @var string
     */
    protected $hashids = 'product';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'price'
    ];

    public function getLocalenameAttribute()
    {
        $name = json_decode($this->name, true);
        if (empty($name[config('app.locale')])) {
            return null;
        }
        return $name[config('app.locale')];
    }
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

    public function page()
    {
        return $this->morphOne(Page::class, 'element');
    }
}
