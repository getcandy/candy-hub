<?php

namespace GetCandy\Api\Countries\Models;

use GetCandy\Api\Scaffold\BaseModel;

class Country extends BaseModel
{
    /**
     * @var string
     */
    protected $hashids = 'main';

    protected $fillable = [
        'name',
        'iso_a_2',
        'iso_a_3',
        'iso_numeric',
        'region',
        'sub_region'
    ];
}
