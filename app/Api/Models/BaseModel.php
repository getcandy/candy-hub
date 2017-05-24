<?php

namespace GetCandy\Api\Models;

use Illuminate\Database\Eloquent\Model;
use GetCandy\Api\Traits\Hashids;

abstract class BaseModel extends Model
{
    use Hashids;
}
