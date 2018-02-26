<?php

namespace GetCandy\Importers\Aqua\Models\Products;

use GetCandy\Importers\Aqua\Models\BaseModel;
use GetCandy\Importers\Aqua\Models\UserGroups\UserGroup;
use GetCandy\Api\Customers\Models\CustomerGroup;

class ProductPrice extends BaseModel
{
    protected $table = 'cscart_product_prices';

    /**
     * Convert the model's attributes to an array.
     *
     * @return array
     */
    public function attributesToArray()
    {
        $group = str_slug($this->group->descriptions()->first()->usergroup);
        return [
            'lower_limit' => $this->lower_limit,
            'customer_group_id' => CustomerGroup::where('handle', '=', $group)->first()->encodedId(),
            'price' => $this->price
        ];
    }

    protected function group()
    {
        return $this->belongsTo(UserGroup::class, 'usergroup_id', 'usergroup_id');
    }
}
