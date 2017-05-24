<?php

namespace GetCandy\Api\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $hashids = 'currency';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'name', 'enabled', 'format', 'exchange_rate', 'decimal_point', 'thousand_point', 'default',
    ];
}
