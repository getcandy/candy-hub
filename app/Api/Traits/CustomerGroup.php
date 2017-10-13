<?php

namespace GetCandy\Api\Traits;

use GetCandy\Api\Scopes\CustomerGroupScope;

trait CustomerGroup
{

    public static function bootCustomerGroup()
    {
        static::addGlobalScope(new CustomerGroupScope);
    }
}
