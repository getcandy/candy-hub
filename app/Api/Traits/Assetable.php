<?php

namespace GetCandy\Api\Traits;

use GetCandy\Api\Assets\Models\Asset;

trait Assetable
{
    public function assets()
    {
        return $this->morphMany(Asset::class, 'assetable');
    }
}
