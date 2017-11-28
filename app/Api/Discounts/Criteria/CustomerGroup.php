<?php
namespace GetCandy\Api\Discounts\Criteria;

use GetCandy\Api\Discounts\Contracts\DiscountCriteriaContract;

class CustomerGroup implements DiscountCriteriaContract
{
    protected $value;

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getLabel()
    {
        return 'Customer Group';
    }

    public function getHandle()
    {
        return 'customer_group';
    }

    public function check($user)
    {
        return app('api')->customerGroups()->userIsInGroup($this->value, $user);
    }
}
