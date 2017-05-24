<?php

namespace GetCandy\Api\Models;

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
