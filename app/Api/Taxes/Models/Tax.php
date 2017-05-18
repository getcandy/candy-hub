<?php

namespace GetCandy\Api\Taxes\Models;

use GetCandy\Api\Traits\Hashids;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    use Hashids;

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
