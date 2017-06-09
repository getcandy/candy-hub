<?php

namespace GetCandy\Api\Products\Models;

use GetCandy\Api\Attributes\Models\Attribute;
use GetCandy\Api\Collections\Models\Collection;
use GetCandy\Api\Pages\Models\Page;
use GetCandy\Api\Routes\Models\Route;
use GetCandy\Api\Scaffold\BaseModel;
use GetCandy\Api\Traits\Indexable;
use GetCandy\Http\Transformers\Fractal\ProductTransformer;
use GetCandy\Api\Layouts\Models\Layout;

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

    /**
     * Get the attributes associated to the product
     * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function attributes()
    {
        return $this->belongsToMany(Attribute::class)->withTimestamps();
    }

    /**
     * Get the attributes associated to the product
     * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function collections()
    {
        return $this->belongsToMany(Collection::class)->withTimestamps();
    }

    /**
     * Get the related family
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function family()
    {
        return $this->belongsTo(ProductFamily::class, 'product_family_id');
    }

    /**
     * Get the products page
     * @return Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function page()
    {
        return $this->morphOne(Page::class, 'element');
    }

    public function layout()
    {
        return $this->belongsTo(Layout::class);
    }

    public function route()
    {
        return $this->morphOne(Route::class, 'element');
    }
}
