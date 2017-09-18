<?php

namespace GetCandy\Http\Validators\Api;

class CategoriesValidator
{
    /**
     * Validates the name for an attribute doesn't exist in the same group
     * @param  String $attribute
     * @param  String $value
     * @return Bool
     */
    public function uniqueCategoryAttributeData($attribute, $value, $parameters, $validator)
    {
        return app('api')->categories()->uniqueAttribute($attribute, $value);
    }

}
