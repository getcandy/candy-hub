<?php

namespace GetCandy\Api\Repositories\Eloquent;

use GetCandy\Api\Models\Currency;
use Illuminate\Http\Request;

class CurrencyRepository extends BaseRepository
{
    public function __construct()
    {
        $this->label = 'name';
        $this->model = new Currency();
    }
}
