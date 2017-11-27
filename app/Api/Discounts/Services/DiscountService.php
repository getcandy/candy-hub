<?php

namespace GetCandy\Api\Discounts\Services;

use GetCandy\Api\Scaffold\BaseService;
use GetCandy\Api\Discounts\Models\Discount;

class DiscountService extends BaseService
{
    /**
     * Create a discount
     *
     * @param array $data
     * 
     * @return Discount
     */
    public function create(array $data)
    {
        $discount = new Discount;
        $discount->name = $data['name'];
        $discount->value = $data['value'];
        $discount->result = $data['result'];

        $discount->save();
        foreach ($data['groups'] as $group) {
            $groupModel = $discount->groups()->create();
            foreach ($group as $item) {
                $groupModel->items()->create($item);
            }
        }
        dd($data);
    }
}
