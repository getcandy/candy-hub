<?php

namespace GetCandy\Api\Assets\Services;

use GetCandy\Api\Assets\Models\Asset;
use GetCandy\Api\Assets\Models\AssetTransform;
use GetCandy\Api\Assets\Models\Transform;
use GetCandy\Api\Scaffold\BaseService;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Image;
use Storage;

class AssetTransformService extends BaseService
{
    public function __construct()
    {
        $this->model = new Transform;
    }

    public function getByHandle($handle)
    {
        return $this->model->where('handle', '=', $handle)->first();
    }

    public function getByHandles(array $handles)
    {
        return $this->model->whereIn('handle', $handles)->get();
    }
    protected function process($transformer, $asset)
    {
        // First, we need to get the actual file.
        $source = $asset->source;
        $path = $asset->location;

        try {
            $file = Storage::disk($asset->source->disk)->get($path . '/'  . $asset->filename);
        } catch (FileNotFoundException $e) {
            return false;
        }

        // You can't transform a PDF so...
        try {
            $image = Image::make($file);
        } catch (NotReadableException $e) {
            return false;
        }

        $width = $transformer->width;
        $height = $transformer->height;

        // Lets sort out the width and height
        switch ($transformer->mode) {
            case 'fit':
                $image->resize($width, $height, function ($constraint) {
                    $constraint->aspectRatio();
                });
                break;
            case 'fit-crop':
                $image->resize($width, $height, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $image->crop($width, $height);
                break;
            case 'stretch':
                dd('Do the stretch');
                break;
            default:
                $image->crop($width, $height);
        }

        $thumbPath = $path . '/' . str_plural($transformer->handle);

        $assetTransform = new AssetTransform;
        $assetTransform->asset()->associate($asset);
        $assetTransform->transform()->associate($transformer);

        $assetTransform->location = $thumbPath;
        $assetTransform->filename = $transformer->handle . '_' . $asset->filename;
        $assetTransform->file_exists = true;


        $assetTransform->save();

        Storage::disk($source->disk)->put(
            $assetTransform->location . '/' . $assetTransform->filename,
            $image->stream()->getContents()
        );
    }

    public function transform($ref, Asset $asset)
    {
        if (is_array($ref)) {
            $transformers = $this->getByHandles($ref);
        } else {
            if ($ref instanceof $this->model) {
                $transformer = $ref;
            } else {
                $transformer = $this->getByHandle($ref);
            }
            $transformers = collect($transformer);
        }
        foreach ($transformers as $transformer) {
            $this->process($transformer, $asset);
        }
    }
}