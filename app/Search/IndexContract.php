<?php

namespace GetCandy\Search;

use Illuminate\Database\Eloquent\Model;

interface IndexContract
{
    public function indexObject(Model $model);
}
