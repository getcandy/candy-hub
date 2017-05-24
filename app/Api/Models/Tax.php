<?php

namespace GetCandy\Api\Models;

class Tax extends BaseModel
{
    protected $hashids = 'tax';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'default',
        'percentage'
    ];
}
