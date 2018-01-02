<?php

namespace GetCandy\Api\Discounts\Services;

use Carbon\Carbon;
use GetCandy\Api\Attributes\Events\AttributableSavedEvent;
use GetCandy\Api\Discounts\Discount as DiscountFactory;
use GetCandy\Api\Discounts\Models\Discount;
use GetCandy\Api\Discounts\RewardSet;
use GetCandy\Api\Scaffold\BaseService;
use GetCandy\Api\Discounts\Models\DiscountCriteriaItem;

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
        $discount->attribute_data = $data;
        $discount->start_at = Carbon::now();
        $discount->status = 'draft';
        $discount->save();

        event(new AttributableSavedEvent($discount));

        return $discount;
    }

    /**
     * Update an existing discount
     *
     * @param string $id
     * @param array $data
     * 
     * @return Discount
     */
    public function update($id, array $data)
    {
        $discount = $this->getByHashedId($id);
        $discount->start_at = Carbon::parse($data['start_at']);
        $discount->end_at = Carbon::parse($data['start_at']);
        $discount->priority = $data['priority'];
        $discount->stop_rules = $data['stop_rules'];
        $discount->status = $data['status'];

        $discount->save();

        // event(new AttributableSavedEvent($discount));

        if (!empty($data['rewards'])) {
            $discount->rewards()->delete();
            foreach ($data['rewards'] as $reward) {
                $discount->rewards()->create($reward);
            }
        }

        if (!empty($data['sets']['data'])) {
            $discount->sets()->delete();
            foreach ($data['sets']['data'] as $set) {
                $groupModel = $discount->sets()->create([
                    'scope' => $set['scope'],
                    'outcome' => $set['outcome']
                ]);

                if (!empty($set['items']['data'])) {
                    $set['items'] = $set['items']['data'];
                }
                foreach ($set['items'] as $item) {
                    $groupModel->items()->create($item);
                }
            }
        }
        
        return $discount;
    }

    /**
     * Get All the discounts
     *
     * @return array
     */
    public function get()
    {
        return $this->model->orderBy('priority', 'desc')->with(['sets', 'sets.items'])->get();
    }

    public function getByCoupon($coupon)
    {
        return DiscountCriteriaItem::where('criteria->value', '=', $coupon)->first();
    }

    public function parse($discounts)
    {
        $sets = [];
        foreach ($discounts as $index => $discount) {
            $factory = new DiscountFactory();
            $factory->setModel($discount);
            $factory->stop = $discount->stop_rules;

            $rewardSet = new RewardSet;
            
            foreach ($discount->rewards as $reward) {
                $rewardSet->add([
                    'type' => $reward->type,
                    'value' => $reward->value
                ]);
            }

            $factory->setReward($rewardSet);

            foreach ($discount->sets as $set) {
                $criteriaSet = new \GetCandy\Api\Discounts\CriteriaSet;
                $criteriaSet->scope = $set['scope'];
                $criteriaSet->outcome = $set['outcome'];
                foreach ($set->items as $item) {
                    $criteriaSet->add($item);
                }
                $sets[] = $factory->addCriteria($criteriaSet);
            }
        }
        return collect($sets);
    }
}
