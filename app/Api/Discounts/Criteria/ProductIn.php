<?php
namespace GetCandy\Api\Discounts\Criteria;

use GetCandy\Api\Discounts\Contracts\DiscountCriteriaContract;

class ProductIn implements DiscountCriteriaContract
{
    protected $criteria;

    public function getArea()
    {
        return 'catalog';
    }

    public function setCriteria($criteria)
    {
        $this->criteria = json_decode($criteria, true);
    }

    public function getCriteria()
    {
        return $this->criteria;
    }

    protected function getRealIds()
    {
        $ids = [];
        if (!empty($this->criteria['products'])) {
            $ids = app('api')->products()->getDecodedIds($this->criteria['products']);
        }
        return collect($ids);
    }

    public function check($user, $product)
    {
        return $this->getRealIds()->contains($product->id);
    }
}
