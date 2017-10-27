<?php

namespace GetCandy\Importers\Aqua\Models;

use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    protected $connection = 'aquaspa';
}
