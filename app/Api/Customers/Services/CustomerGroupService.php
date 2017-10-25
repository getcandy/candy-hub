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

    public function getGroupsWithAvailability($model, $relation)
    {
        $groups = $this->model->with([$relation => function ($q) use ($model, $relation) {
            $q->where($relation . '.id', $model->id);
        }])->get();
        foreach ($groups as $group) {
            $model = $group->{$relation}->first();
            $group->published_at = $model ? $model->pivot->published_at : null;
            $group->visible = $model ? $model->pivot->visible : false;
        }
        return $groups;
    }

    public function getGuestId()
    {
        return $this->model->where('handle', '=', 'guest')->pluck('id')->first();
    }
}
