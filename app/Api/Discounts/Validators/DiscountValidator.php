<?php

namespace GetCandy\Api\Discounts\Validators;

class DiscountValidator
{
    public function validate($attribute, $value, $parameters, $validator)
    {
        foreach ($value as $criteria) {
            $previous = [];
            foreach ($criteria as $item) {
                if (!count(array_diff($item, $previous))) {
                    return false;
                }
                $previous = $item;
            }
        }
        return true;
    }
}
