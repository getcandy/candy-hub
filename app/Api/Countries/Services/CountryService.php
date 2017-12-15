<?php
namespace GetCandy\Api\Countries\Services;

use GetCandy\Api\Scaffold\BaseService;
use GetCandy\Api\Countries\Models\Country;

class CountryService extends BaseService
{
    public function __construct()
    {
        $this->model = new Country;
    }

    public function getGroupedByRegion()
    {
        $countries = $this->model->get();

        return $countries->groupBy('region');
    }
}
