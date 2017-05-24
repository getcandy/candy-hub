<?php

namespace GetCandy\Api\Models;

use Illuminate\Database\Eloquent\Model;
use GetCandy\Api\Traits\Hashids;

class Channel extends Model
{
    use Hashids;

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
