<?php

namespace GetCandy\Api\Routes\Services;

use GetCandy\Api\Scaffold\BaseService;
use GetCandy\Api\Routes\Models\Route;

class RouteService extends BaseService
{
    public function __construct()
    {
        $this->model = new Route;
    }

    public function getBySlug($slug)
    {
        $route = $this->model->where('slug', '=', $slug)->firstOrFail();

        app()->setLocale($route->locale);

        return $route;
    }
}
