<?php

namespace GetCandy\Http\Validators\Api;

class RoutesValidator
{
    /**
     * Validates the slug for a route
     * @param  String $attribute
     * @param  String $value
     * @return Bool
     */
    public function uniqueRoute($attribute, $value, $parameters, $validator)
    {
        return app('api')->routes()->uniqueSlug($value);
    }

}
