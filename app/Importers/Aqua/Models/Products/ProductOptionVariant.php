<?php

namespace GetCandy\Importers\Aqua\Models\Products;

use GetCandy\Importers\Aqua\Models\BaseModel;

class ProductOptionVariant extends BaseModel
{
    protected $table = 'cscart_product_option_variants';

    public function option()
    {
        return $this->belongsTo(ProductOption::class, 'option_id', 'option_id');
    }
    public function description()
    {
        return $this->hasMany(ProductOptionVariantDescription::class, 'variant_id', 'variant_id');
    }
}
