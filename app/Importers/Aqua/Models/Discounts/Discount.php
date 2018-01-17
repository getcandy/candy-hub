<?php
namespace GetCandy\Importers\Aqua\Models\Discounts;

use GetCandy\Importers\Aqua\Models\BaseModel;
use GetCandy\Importers\Aqua\Decorator;
use Carbon\Carbon;

class Discount extends BaseModel
{
    protected $table = 'cscart_promotions';

    public function getConditionsAttribute($val)
    {
        return unserialize($val);
    }

    public function getBonusesAttribute($val)
    {
        return unserialize($val);
    }

    public function descriptions()
    {
        return $this->hasMany(DiscountDescription::class, 'promotion_id', 'promotion_id');
    }

    public function attributesToArray()
    {
        $decorator = new Decorator;
        $data = $decorator->getdata($this, 'short_description', 'name');

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
        
        return array_merge($data, [
            'stop_rules' => $this->stop == 'Y',
            'uses' => $this->number_of_usages,
            'start_at' => $this->from_date ? Carbon::createFromTimestamp($this->from_date) : Carbon::now(),
            'end_at' => $this->to_date ? Carbon::createFromTimestamp($this->to_date) : null,
            'channels' => ['data' => $channelData],
            'conditions' => $this->conditions,
            'bonuses' => $this->bonuses
        ]);
    }
}
