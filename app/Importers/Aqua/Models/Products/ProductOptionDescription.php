<?php

namespace GetCandy\Importers\Aqua\Models\Products;

use GetCandy\Importers\Aqua\Models\BaseModel;

class ProductOptionDescription extends BaseModel
{
    protected $table = 'cscart_product_options_descriptions';

    public function option()
    {
        return $this->belongsTo(ProductOption::class, 'option_id', 'option_id');
    }
}
