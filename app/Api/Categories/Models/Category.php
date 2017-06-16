<?php

namespace GetCandy\Api\Categories\Models;

use GetCandy\Api\Scaffold\BaseModel;
use Kalnoy\Nestedset\NodeTrait;

class Category extends BaseModel
{
    use NodeTrait;

    protected $hashids = 'main';
}
