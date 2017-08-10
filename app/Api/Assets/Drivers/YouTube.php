<?php

namespace GetCandy\Api\Assets\Drivers;

use GetCandy\Api\Assets\Contracts\AssetDriverContract;
use GetCandy\Api\Assets\Models\Asset;
use GetCandy\Api\Assets\Jobs\GenerateTransforms;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class YouTube extends BaseUrlDriver implements AssetDriverContract
{

    protected $manager;

    public function __construct()
    {
        $this->manager = app('youtube');
    }

    /**
     * In order of trying...
     * @var array
     */
    protected $thumbnailSizes = [
        'maxresdefault',
        'sddefault',
        'mqdefault',
        'hqdefault',
        'default'
    ];

    public function getUniqueId($url)
    {
        return $this->manager->parseVidFromURL($url);
    }

    public function getThumbnail($url)
    {
        $videoId = $this->getUniqueId($url);
        $thumbnail = null;

        foreach ($this->thumbnailSizes as $size) {
            $thumbnailUrl = "https://img.youtube.com/vi/{$videoId}/{$size}.jpg";
            if ($thumbnail) {
                continue;
            }
            $thumbnail = $this->getImageFromUrl($thumbnailUrl);
        }

        if (!$thumbnail) {
            return false;
        }

        return $thumbnail;
    }

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

        if ($model->assets()->count()) {
            // Get anything that isn't an "application";
            $image = $model->assets()->where('kind', '!=', 'application')->first();
            if (!$image) {
                $asset->primary = true;
            }
        }
        $model->assets()->save($asset);
        dispatch(new GenerateTransforms($asset));
        return $asset;
    }

    /**
     * Prepares the asset
     * @param  array $data
     * @param  Model $model
     * @return Asset
     */
    public function prepare($data, $source)
    {
        $info = $this->getVideoInfo($data['url']);
        $asset = new Asset([
            'location' => $data['url'],
            'title' => $info['title'],
            'kind' => 'youtube',
            'external' => true
        ]);
        $asset->source()->associate($source);
        return $asset;
    }

    public function getVideoInfo($url)
    {
        $client = new Client();
        try {
            $response = $client->request('GET', 'https://www.youtube.com/oembed', [
                'query' => [
                    'format' => 'json',
                    'url' => $url
                ]
            ]);
            return json_decode($response->getBody()->getContents(), true);
        } catch (ClientException $e) {
            //
        }
        return null;
    }
}
