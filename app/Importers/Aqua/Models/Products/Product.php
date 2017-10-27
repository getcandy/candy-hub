<?php

namespace GetCandy\Importers\Aqua\Models\Products;

use GetCandy\Importers\Aqua\Models\BaseModel;
use GetCandy\Importers\Aqua\Models\Assets\ImageLink;

class Product extends BaseModel
{
    protected $table = 'cscart_products';

    public function descriptions()
    {
        return $this->hasMany(ProductDescription::class, 'product_id', 'product_id');
    }

    public function images()
    {
        return $this->hasMany(ImageLink::class, 'object_id', 'product_id')->where('object_type', '=', 'product');
    }
}
