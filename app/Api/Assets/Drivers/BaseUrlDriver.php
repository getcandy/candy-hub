<?php

namespace GetCandy\Api\Assets\Drivers;

use GetCandy\Api\Assets\Contracts\AssetDriverContract;
use GetCandy\Api\Assets\Models\Asset;
use Illuminate\Database\Eloquent\Model;
use Intervention\Image\Exception\NotReadableException;
use Illuminate\Support\Str;

abstract class BaseUrlDriver
{
    /**
     * @var bool
     */
    protected $upload = true;

    protected $hashedName = null;

    /**
     * @param array $data
     * @param       $model
     *
     * @return \GetCandy\Api\Assets\Models\Asset
     */
    public function process(array $data, $model)
    {
        $source = app('api')->assetSources()->getByHandle($model->settings['asset_source']);
        $asset = $this->prepare($data, $source);
        $model->assets()->save($asset);
        return $asset;
    }

    /**
     * Generates a hashed name
     */
    public function hashName()
    {
        return $this->hashedName ?: $this->hashedName = Str::random(40);
    }

    public function getImageFromUrl($url)
    {
        try {
            $image = app('image')->make($url);
        } catch (NotReadableException $e) {
            $image = null;
        }
        return $image;
    }

    abstract public function getThumbnail($url);
    // abstract public function getSize();
}
