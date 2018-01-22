<?php
namespace GetCandy\Api\Discounts\Criteria;

use GetCandy\Api\Discounts\Contracts\DiscountCriteriaContract;

class Products implements DiscountCriteriaContract
{
    protected $criteria;

    public function getArea()
    {
        return 'catalog';
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function check($user, $product = null, $basket = null)
    {
        // If we are not checking a product, its a basket...
        if ($product) {
            return $this->value->contains($product->id);
        }
        $check = false;
        foreach ($basket->lines as $line) {
            $check = $this->value->contains($line->variant->product->id);
        }
        return $check;
    }
}
