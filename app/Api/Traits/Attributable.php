<?php

namespace GetCandy\Api\Traits;

use GetCandy\Api\Attributes\Models\Attribute;

trait Attributable
{
    /**
     * Get the attributes associated to the product family
     * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function attributes()
    {
        return $this->belongsToMany(Attribute::class)->withTimestamps();
    }

    public function attribute($handle, $channel = null)
    {
        $locale = app()->getLocale();
        if (empty($this->attribute_data[$handle][$locale])) {
            return null;
        }
        return $this->attribute_data[$handle][$locale];
    }

    public function getNameAttribute()
    {
        return $this->attribute('name');
    }
}
