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

    public function getRealIds()
    {
        $ids = [];
        if (!empty($this->criteria['value'])) {
            $ids = app('api')->products()->getDecodedIds($this->criteria['value']);
        }
        return collect($ids);
    }

    public function check($user, $product = null, $basket = null)
    {
        // If we are not checking a product, its a basket...
        if ($product) {
            return $this->getRealIds()->contains($product->id);
        }
        $check = false;
        foreach ($basket->lines as $line) {
            $check = $this->getRealIds()->contains($line->variant->product->id);
        }
        return $check;
    }
}
