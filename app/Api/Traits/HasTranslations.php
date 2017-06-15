<?php

namespace GetCandy\Api\Traits;

trait HasTranslations
{
    /**
     * Sets the name attribute to a json string
     * @param array $value
     */
    public function setNameAttribute(array $value)
    {
        if (is_array($value)) {
            $this->attributes['name'] = json_encode($value);
        } else {
            $this->attributes['name'] = $value;
        }
    }

    public function getNameAttribute($value)
    {
        return json_decode($value, true);
    }
}
