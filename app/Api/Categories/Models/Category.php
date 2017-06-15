<?php

namespace GetCandy\Api\Categories\Models;

use GetCandy\Api\Scaffold\BaseModel;
use Kalnoy\Nestedset\NodeTrait;
use GetCandy\Api\Traits\HasTranslations;

class Category extends BaseModel
{
    use NodeTrait, HasTranslations;

    protected $hashids = 'main';
}
