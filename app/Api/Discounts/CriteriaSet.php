<?php

namespace GetCandy\Api\Discounts;

class CriteriaSet
{
    protected $sets = [];

    protected $value;

    protected $type;

    public function add($set)
    {
        $classname = config('getcandy.discounters.' . $set->source);
        $criteria = new $classname;
        $criteria->setValue($set->value);
        $this->sets[] = $criteria;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getType()
    {
        return $this->type;
    }

    public function process($user)
    {
        $apply = false;
        foreach ($this->sets as $set) {
            $apply = $set->check($user);
        }
        return $apply;
    }
}
