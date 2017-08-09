<?php

namespace GetCandy\Api\Assets\Drivers;

use GetCandy\Api\Assets\Contracts\AssetDriverContract;
use GetCandy\Api\Assets\Models\Asset;

class YouTube extends BaseUrlDriver implements AssetDriverContract
{
    public function prepare($data, $model)
    {
        return new Asset([
            'location' => $data['url'],
            'kind' => 'youtube',
            'external' => true
        ]);
    }
}