<?php

namespace GetCandy\Api\Discounts\Services;

use GetCandy\Api\Scaffold\BaseService;
use GetCandy\Api\Discounts\Models\Discount;
use GetCandy\Api\Attributes\Events\AttributableSavedEvent;
use GetCandy\Api\Discounts\Discount as DiscountFactory;
use GetCandy\Api\Discounts\RewardSet;

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
        $discount->start_at = \Carbon\Carbon::parse($data['start']);
        $discount->end_at = \Carbon\Carbon::parse($data['start']);
        $discount->priority = $data['priority'];
        $discount->stop_rules = $data['stop_rules'];
        $discount->status = $data['status'];
        $discount->channel_id = app('api')->channels()->getByHashedId($data['channel'])->id;
        
        $discount->save();

        event(new AttributableSavedEvent($discount));

        foreach ($data['rewards'] as $reward) {
            $discount->rewards()->create($reward);
        }

        foreach ($data['sets'] as $set) {
            $groupModel = $discount->sets()->create([
                'scope' => $set['scope'],
                'outcome' => $set['outcome']
            ]);
            foreach ($set['criteria'] as $item) {
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
        return $this->model->orderBy('priority', 'desc')->with(['sets', 'sets.items'])->get();
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
                    'payload' => json_decode($reward->payload, true)
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
            }
            $sets[] = $factory->addCriteria($criteriaSet);
        }
        return collect($sets);
    }
}
