<?php

namespace GetCandy\Importers\Aqua\Models\Shipping;

use GetCandy\Importers\Aqua\Models\BaseModel;
use GetCandy\Importers\Aqua\Decorator;
use GetCandy\Importers\Aqua\Models\UserGroups\UserGroup;

class ShippingMethod extends BaseModel
{
    protected $table = 'cscart_shippings';

    public function descriptions()
    {
        return $this->hasMany(ShippingDescription::class, 'shipping_id', 'shipping_id');
    }

    public function rates()
    {
        return $this->hasMany(ShippingRate::class, 'shipping_id', 'shipping_id');
    }

    public function usergroups()
    {
        // Convert column to an array...
        return UserGroup::whereIn('usergroup_id', explode(',', $this->usergroup_ids));
    }

    /**
     * Convert the model's attributes to an array.
     *
     * @return array
     */
    public function attributesToArray()
    {
        $decorator = new Decorator;
        $data  = $decorator->getdata($this, 'delivery_time', 'shipping');

        $channelData = [
            [
                'id' => app('api')->channels()->getByHandle('aqua-spa-supplies')->encodedId(),
                'published_at' => $this->status == 'A' ? \Carbon\Carbon::now() : null
            ],
            [
                'id' => app('api')->channels()->getByHandle('europe-aqua-spa-supplies')->encodedId(),
                'published_at' => $this->status == 'A' ? \Carbon\Carbon::now() : null
            ]
        ];

        unset($data['url']);

        return array_merge($data, [
            'type' => 'standard',
            'channels' => ['data' => $channelData],
            'customer_groups' => [
                'data' => $this->getCustomerGroups()
            ]
        ]);
    }
}
