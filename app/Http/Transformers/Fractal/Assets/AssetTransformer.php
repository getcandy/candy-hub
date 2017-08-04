<?php

namespace GetCandy\Http\Transformers\Fractal\Assets;

use GetCandy\Api\Assets\Models\Asset;
use GetCandy\Http\Transformers\Fractal\BaseTransformer;
use Storage;


class AssetTransformer extends BaseTransformer
{
    protected $availableIncludes = [
        'group'
    ];

    /**
     * Decorates the attribute object for viewing
     * @param  Attribute $product
     * @return array
     */
    public function transform(Asset $asset)
    {
        $url = Storage::disk($asset->source->disk)->url($asset->filename);
        return [
            'id' => $asset->encodedId(),
            'title' => $asset->title,
            'caption' => $asset->caption,
            'original_filename' => $asset->original_filename,
            'size' => $asset->size,
            'width' => $asset->width,
            'height' => $asset->height,
            'kind' => $asset->kind,
            'url' => $this->getUrl($asset)
        ];
    }

    protected function getUrl($asset)
    {
        $source = $asset->source;
        $path = ($source->path ? $source->path . '/' : null) . $asset->location . '/' . $asset->filename;
        return Storage::disk($asset->source->disk)->url($path);
    }
}
