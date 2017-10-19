<?php

namespace GetCandy\Api\Categories\Models;

use GetCandy\Api\Channels\Models\Channel;
use GetCandy\Api\Products\Models\Product;
use GetCandy\Api\Routes\Models\Route;
use GetCandy\Api\Scaffold\BaseModel;
use GetCandy\Api\Traits\Assetable;
use GetCandy\Api\Traits\HasAttributes;
use Kalnoy\Nestedset\NodeTrait;

class Category extends BaseModel
{
    use NodeTrait, HasAttributes, Assetable;

    protected $hashids = 'main';

    protected $settings = 'products';

    protected $fillable = [
        'attribute_data', 'parent_id'
    ];

    public function routes()
    {
        return $this->morphMany(Route::class, 'element');
    }

    public function hasChildren()
    {
        $children = $this->hasMany(Category::class, 'parent_id')
            ->count();

        return (bool) $children;
    }

    public function parent()
    {
        return $this->belongsTo($this, 'parent_id');
    }

    public function getProductCount()
    {
        return $this->belongsToMany(Product::class, 'product_categories')->count();
    }

    /**
     * Get the attributes associated to the product
     * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function channels()
    {
        return $this->belongsToMany(Channel::class)->withPivot('published_at');
    }

}