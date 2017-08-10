<?php

namespace GetCandy\Http\Validators\Api;

class AssetValidator
{
    public function validAssetUrl($attribute, $value, $parameters, $validator)
    {
        if (empty($parameters[0])) {
            return false;
        }

        $method = 'validate' . title_case($parameters[0]) . 'Url';

        if (method_exists($this, $method)) {
            return call_user_func([$this, $method], $value);
        }

        return false;
    }

    protected function validateYoutubeUrl($url)
    {
        $driver = app('api')->assets()->getDriver('youtube');
        return (bool) $driver->getVideoInfo($url);
    }
}
