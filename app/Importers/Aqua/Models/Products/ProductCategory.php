<?php

namespace GetCandy\Importers\Aqua\Models\Products;

use GetCandy\Importers\Aqua\Models\BaseModel;
use GetCandy\Importers\Aqua\Models\Categories\Category;

class ProductCategory extends BaseModel
{
    protected $table = 'cscart_products_categories';

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
