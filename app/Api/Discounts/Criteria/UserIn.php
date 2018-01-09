<?php
namespace GetCandy\Api\Discounts\Criteria;

use GetCandy\Api\Discounts\Contracts\DiscountCriteriaContract;

class UserIn implements DiscountCriteriaContract
{
    protected $criteria;

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
        return collect(app('api')->users()->getDecodedIds($this->criteria['value']));
    }

    public function check($user)
    {
        return false;
        return $this->getRealIds()->contains($user->id);
    }
}
