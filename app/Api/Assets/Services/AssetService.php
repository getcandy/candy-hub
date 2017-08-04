<?php

namespace GetCandy\Api\Assets\Services;

use GetCandy\Api\Assets\Models\Asset;
use GetCandy\Api\Scaffold\BaseService;
use Illuminate\Database\Eloquent\Model;
use Image;
use Intervention\Image\Exception\NotReadableException;

class AssetService extends BaseService
{
    /**
     * @param                                     $file
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return \GetCandy\Api\Assets\Models\Asset
     */
    public function upload($file, Model $model)
    {
        // Get our product settings;
        $settings = $model->settings;
        $source = app('api')->assetSources()->getByHandle($settings['asset_source']);


        $asset = new Asset;
        $asset->kind = $file->getMimeType();
        $asset->size = $file->getSize();
        $asset->original_filename = $file->getClientOriginalName();
        $asset->filename = $file->hashName();
        $asset->source()->associate($source);
        $asset->extension = $file->extension();

        // Get the relative path to where this needs to be stored.
        $path = $source->path . '/' . substr($asset->filename, 0, 2);

        $asset->location = $path;

        // Whether its an image or not we're always storing it so lets do it now.
        $file->storeAs($path, $asset->filename, $source->disk);

        // Assume its not an image.
        $image = false;

        try {
            $image = Image::make($file);
        } catch (NotReadableException $e) {
            // Fall through
        }

        if ($image) {
            $asset->width = $image->width();
            $asset->height = $image->height();
        }

        $model->assets()->save($asset);
        app('api')->transforms()->transform(array_merge(
            ['thumbnail'],
            $settings['transforms']
        ), $asset);

        return $asset;
    }
}
