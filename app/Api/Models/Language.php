<?php

namespace GetCandy\Api\Models;

use GetCandy\Api\Traits\Hashids;

class Language extends BaseModel
{
    protected $hashids = 'language';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'name', 'default'
    ];
}
