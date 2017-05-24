<?php

namespace GetCandy\Api\Models;

use GetCandy\Api\Traits\Hashids;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use Hashids;

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
