<?php

namespace GetCandy\Api\Categories\Models;

use GetCandy\Api\Scaffold\BaseModel;
use Kalnoy\Nestedset\NodeTrait;
use GetCandy\Api\Traits\HasAttributes;

class Category extends BaseModel
{
    use NodeTrait, HasAttributes;

    protected $hashids = 'main';

    protected $fillable = [
        'attribute_data', 'parent_id'
    ];

}