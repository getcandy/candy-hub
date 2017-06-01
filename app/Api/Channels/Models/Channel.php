<?php

namespace GetCandy\Api\Channels\Models;

use GetCandy\Api\Scaffold\BaseModel;

class Channel extends BaseModel
{
    protected $hashids = 'channel';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'default'
    ];
}
