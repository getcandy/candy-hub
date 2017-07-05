<?php

namespace GetCandy\Api\Categories\Models;

use GetCandy\Api\Scaffold\BaseModel;
use Kalnoy\Nestedset\NodeTrait;
use GetCandy\Api\Traits\Attributable;

class Category extends BaseModel
{
    use NodeTrait, Attributable;

    protected $hashids = 'main';
}
