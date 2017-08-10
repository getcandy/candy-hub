<?php

namespace GetCandy\Api\Assets\Drivers;

use Vinkla\Vimeo\VimeoManager;

class Vimeo extends BaseUrlDriver
{
    /**
     * @var Vinkla\Vimeo\VimeoManager
     */
    protected $manager;

    /**
     * @var string
     */
    protected $handle = 'vimeo';

    /**
     * @var string
     */
    protected $oemUrl = 'https://vimeo.com/api/oembed.json';

    public function __construct(VimeoManager $vimeo)
    {
        $this->manager = $vimeo;
    }

    public function getInfo($url)
    {
        if (!$this->info) {
            return $this->info = $this->getOemData([
                'url' => $url
            ]);
        }
        return $this->info;
    }
}
