<?php

namespace GetCandy\Api\Assets\Drivers;

use GetCandy\Api\Assets\Contracts\AssetDriverContract;
use GetCandy\Api\Assets\Models\Asset;
use Illuminate\Database\Eloquent\Model;

abstract class BaseUrlDriver
{
    /**
     * @var bool
     */
    protected $upload = true;

    /**
     * @param array $data
     * @param       $model
     *
     * @return \GetCandy\Api\Assets\Models\Asset
     */
    public function process(array $data, $model)
    {
        $asset = $this->prepare($data, $model);
        $model->assets()->save($asset);
        return $asset;
    }
}
