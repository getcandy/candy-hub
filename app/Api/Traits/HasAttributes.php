<?php

namespace GetCandy\Api\Traits;

use GetCandy\Api\Attributes\Models\Attribute;
use GetCandy\Api\Attributes\Models\AttributeGroup;

trait HasAttributes
{

    /**
     * Get all of the tags for the post.
     */
    public function attributes()
    {
        return $this->morphToMany(Attribute::class, 'attributable');
    }

    public function attributeGroup()
    {
        return $this->hasOne(AttributeGroup::class)->withTimestamps();
    }

    public function attribute($handle, $channel = 'ecommerce', $locale = 'en')
    {
        if (empty($this->attribute_data[$handle][$channel][$locale])) {
            return null;
        }
        return $this->attribute_data[$handle][$channel][$locale];
    }

    public function getNameAttribute()
    {
        return $this->attribute('name');
    }

    public function getAttributeDataAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setAttributeDataAttribute($val)
    {
        $this->attributes['attribute_data'] = json_encode($val);
    }

    /**
     * Prepares the attribute data for saving to the database
     * @param  array  $data
     * @return array
     */
    public function parseAttributeData(array $data)
    {
        $valueMapping = [];
        $structure = $this->getDataMapping();

        foreach ($data as $attribute) {
            // Do this so we can reset the structure without hitting DB again
            $newData[$attribute['key']] = $structure;

            // Set Attribute
            $valueMapping[$attribute['key']][$attribute['channel']][$attribute['locale']] = $attribute['value'];

            // Map the rest of the attribute data
            foreach ($valueMapping as $attribute => $value) {
                foreach ($value as $map => $value) {
                    array_set($newData[$attribute], $map, $value);
                }
            }

        }
        return $newData;
    }

    /**
     * Gets the current attribute data mapping
     * @return Array
     */
    public function getDataMapping()
    {
        $structure = [];
        $languagesArray = [];
        // Get our languages
        $languages = app('api')->languages()->getDataList();
        foreach ($languages as $lang) {
            $languagesArray[$lang->lang] = null;
        }
        // Get our channels
        $channels = app('api')->channels()->getDataList();
        foreach ($channels as $channel) {
            $structure[$channel->handle] = $languagesArray;
        }
        return $structure;
    }
}
