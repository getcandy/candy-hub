<?php
namespace GetCandy\Api\Baskets\Services;

use GetCandy\Api\Scaffold\BaseService;
use GetCandy\Api\Baskets\Models\BasketLine;

class BasketLineService extends BaseService
{
    /**
     * @var Basket
     */
    protected $model;

    public function __construct()
    {
        $this->model = new BasketLine();
    }
}
