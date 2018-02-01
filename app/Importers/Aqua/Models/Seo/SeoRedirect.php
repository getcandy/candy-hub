<?php

namespace GetCandy\Importers\Aqua\Models\Seo;

use GetCandy\Importers\Aqua\Models\BaseModel;
use GetCandy\Importers\Aqua\Models\UserGroups\UserGroup;

class SeoRedirect extends BaseModel
{
    protected $table = 'cscart_seo_redirects';

    public function method()
    {
        return $this->belongsTo(ShippingMethod::class, 'shipping_id', 'shipping_id');
    }

    public function attributesToArray()
    {
        return [
            'src' => str_replace('/', '', $this->src),
            'lang_code' => $this->lang_code
        ];
    }
}
