<?php

namespace GetCandy\Api\Repositories\Eloquent;

use GetCandy\Api\Models\Tax;
use Illuminate\Http\Request;

class TaxRepository extends BaseRepository
{
    public function __construct()
    {
        $this->label = 'name';
        $this->model = new Tax();
    }

    public function getNewSuggestedDefault()
    {
        return $this->model->where('default', '=', false)->first();
    }
}
