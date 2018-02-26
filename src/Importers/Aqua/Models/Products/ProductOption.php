<?php

namespace GetCandy\Importers\Aqua\Models\Products;

use GetCandy\Importers\Aqua\Models\BaseModel;

class ProductOption extends BaseModel
{
    protected $table = 'cscart_product_options';

    public function variants()
    {
        return $this->hasMany(ProductOptionVariant::class, 'option_id', 'option_id');
    }

    public function description()
    {
        return $this->hasMany(ProductOptionDescription::class, 'option_id', 'option_id');
    }
}
