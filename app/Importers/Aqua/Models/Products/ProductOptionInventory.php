<?php

namespace GetCandy\Importers\Aqua\Models\Products;

use GetCandy\Importers\Aqua\Models\BaseModel;
use GetCandy\Importers\Aqua\Models\Assets\ImageLink;

class ProductOptionInventory extends BaseModel
{
    protected $table = 'cscart_product_options_inventory';

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    public function image()
    {
        return $this->belongsTo(ImageLink::class, 'combination_hash', 'object_id')->where('object_type', '=', 'product_option');
    }
}
