<?php

namespace GetCandy\Importers\Aqua\Models\Products;

use GetCandy\Importers\Aqua\Models\BaseModel;
use GetCandy\Importers\Aqua\Models\Channel;
use GetCandy\Importers\Aqua\Models\Products\ProductCategory;
use GetCandy\Importers\Aqua\Models\Assets\ImageLink;
use GetCandy\Importers\Aqua\Decorator;
use Carbon\Carbon;
use GetCandy\Importers\Aqua\Models\UserGroups\UserGroup;

class Product extends BaseModel
{
    protected $table = 'cscart_products';

    public $idref = 'product_id';

    /**
     * Convert the model's attributes to an array.
     *
     * @return array
     */
    public function attributesToArray()
    {
        $decorator = new Decorator;

        $channel = $this->channel;

        $handle = str_slug($channel->company);
        $channelData = [[
            'id' => app('api')->channels()->getByHandle($handle)->encodedId(),
            'published_at' => Carbon::createFromTimestamp($this->timestamp)
        ]];

        $options = [];

        foreach ($this->options as $option) {
            $options[] = $option;
        }

        $customerGroups = [];

        if ($this->usergroups()->count()) {
            foreach ($this->userGroups()->get() as $group) {
                $handle = str_slug($group->descriptions->first()->usergroup);
                $group = app('api')->customerGroups()->getByHandle($handle);
                $customerGroups['data'][] = [
                    'id' => $group->encodedId(),
                    'visible' => $this->status == 'A',
                    'purchasable' => $this->status == 'A'
                ];
            }
        }

        // // Gotta try and get our guest ones out...
        foreach (explode(',', $this->usergroup_ids) as $groupId) {
            if ($groupId == 0) {
                $groups = \GetCandy\Api\Customers\Models\CustomerGroup::all();
                foreach ($groups as $group) {
                    $customerGroups['data'][] = [
                        'id' => $group->encodedId(),
                        'visible' => $this->status == 'A',
                        'purchasable' => $this->status == 'A'
                    ];
                }
            }
        }

        return array_merge($decorator->getdata($this), [
            'family_id' => \GetCandy\Api\Products\Models\ProductFamily::first()->encodedId(),
            'stock' => $this->amount,
            'sku' => $this->product_code,
            'price' => $this->list_price,
            'options' => $options,
            'weight' => $this->weight,
            'channels' => [
                'data' => $channelData
            ],
            'customer_groups' => $customerGroups
        ]);
    }


    public function descriptions()
    {
        return $this->hasMany(ProductDescription::class, 'product_id', 'product_id');
    }

    public function images()
    {
        return $this->hasMany(ImageLink::class, 'object_id', 'product_id')->where('object_type', '=', 'product');
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class, 'company_id', 'company_id');
    }

    public function categories()
    {
        return $this->hasMany(ProductCategory::class, 'product_id', 'product_id');
    }

    public function options()
    {
        return $this->hasMany(ProductOption::class, 'product_id', 'product_id');
    }
    public function usergroups()
    {
        // Convert column to an array...
        return UserGroup::whereIn('usergroup_id', explode(',', $this->usergroup_ids));
    }
}
