<?php

namespace GetCandy\Api\Customers\Services;

use GetCandy\Api\Customers\Models\CustomerGroup;
use GetCandy\Api\Products\Models\Product;
use GetCandy\Api\Scaffold\BaseService;

class CustomerGroupService extends BaseService
{
    public function __construct()
    {
        $this->model = new CustomerGroup;
    }

    /**
     * @param Product|null $product
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Collection|static|static[]
     */
    public function getGroupsWithAvailability(Product $product = null)
    {
        $groups = $this->model->get();
        foreach ($groups as $group) {
            $groupProduct = $group->products()->find($product->id);
            $group->purchasable = $groupProduct ? (bool) $groupProduct->pivot->purchasable : false;
            $group->visible = $groupProduct ? (bool) $groupProduct->pivot->visible : false;
        }
        return $groups;
    }
}
