<?php

namespace GetCandy\Api\Associations\Models;

use GetCandy\Api\Scaffold\BaseModel;

class AssociationGroup extends BaseModel
{
    protected $fillable = ['association_id', 'type'];
}
