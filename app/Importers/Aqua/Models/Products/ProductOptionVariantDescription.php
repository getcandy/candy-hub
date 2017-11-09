<?php

namespace GetCandy\Importers\Aqua\Models\Products;

use GetCandy\Importers\Aqua\Models\BaseModel;

class ProductOptionVariantDescription extends BaseModel
{
    protected $table = 'cscart_product_option_variants_descriptions';

    public function variant()
    {
        return $this->belongsTo(ProductOptionVariant::class, 'variant_id', 'variant_id');
    }
}
