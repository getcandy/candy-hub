<?php

namespace GetCandy\Api\Discounts\Services;

use GetCandy\Api\Scaffold\BaseService;
use GetCandy\Api\Discounts\Models\Discount;

class DiscountService extends BaseService
{
    public function __construct()
    {
        $this->model = new Discount();
    }

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
        foreach ($data['sets'] as $group) {
            $groupModel = $discount->sets()->create();
            foreach ($group as $item) {
                $groupModel->items()->create($item);
            }
        }
        dd($data);
    }

    /**
     * Get All the discounts
     *
     * @return array
     */
    public function get()
    {
        return $this->model->with(['sets', 'sets.items'])->get();
    }

    public function parse($discounts)
    {
        $sets = [];
        foreach ($discounts as $index => $discount) {
            $sets[$index]['name'] = $discount->name;
            $sets[$index]['value'] = $discount->value;
            $sets[$index]['result'] = $discount->result;
            foreach ($discount->sets as $set) {
                $criteriaSet = new \GetCandy\Api\Discounts\CriteriaSet;
                foreach ($set->items as $item) {
                    $criteriaSet->add($item);
                }
            }
            $sets[$index]['criteria'][] = $criteriaSet;
        }
        return $sets;
    }
}
