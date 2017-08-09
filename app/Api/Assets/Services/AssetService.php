<?php

namespace GetCandy\Api\Assets\Services;

use GetCandy\Api\Assets\Jobs\CleanUpAssetFiles;
use GetCandy\Api\Assets\Jobs\GenerateTransforms;
use GetCandy\Api\Assets\Models\Asset;
use GetCandy\Api\Scaffold\BaseService;
use Illuminate\Database\Eloquent\Model;
use Image;
use Intervention\Image\Exception\NotReadableException;

class AssetService extends BaseService
{
    public function __construct()
    {
        $this->model = new Asset;
    }

    protected function getDriver($mimeType)
    {
        $type = explode('/', $mimeType);
        switch ($type[0]) {
            case 'youtube':
                return new \GetCandy\Api\Assets\Drivers\YouTube;
            case 'image':
                return new \GetCandy\Api\Assets\Drivers\Image;
            default:
                return new \GetCandy\Api\Assets\Drivers\File;
        }
    }

    public function upload($data, Model $model, $position = 0)
    {
        if (!empty($data['file'])) {
            $mimeType = $data['file']->getClientMimeType();
        } else {
            $mimeType = $data['mime_type'];
        }
        $driver = app('api')->assets()->getDriver($mimeType);
        $asset = $driver->process(
            $data,
            $model
        );
        return $asset;
    }

    public function updateAll($assets)
    {
        foreach ($assets as $asset) {
            $this->update($asset['id'], $asset);
        }
        return true;
    }
    public function update($id, $data)
    {
        $asset = $this->getByHashedId($id);
        $asset->fill($data);
        $asset->save();
        return $asset;
    }

    public function getAssets(Model $assetable, $params)
    {
        $assets = $assetable->assets();
        if (!empty($params['type'])) {
            if ($params['type'] == 'image') {
                $assets = $assets->where('kind', '=', 'image');
            } else {
                $assets = $assets->where('kind', '!=', 'image');
            }
        }
        return $assets->get();
    }

    /**
     * @param $id
     *
     * @return bool
     */
    public function delete($id)
    {
        $asset = $this->getByHashedId($id);

        dispatch(new CleanUpAssetFiles($asset));

        $asset->delete();

        return true;
    }
}
