<?php

namespace GetCandy\Importers\Aqua\Models\Assets;

use GetCandy\Importers\Aqua\Models\BaseModel;

class ImageLink extends BaseModel
{
    protected $table = 'cscart_images_links';

    public function ref()
    {
        return $this->belongsTo(Image::class, 'detailed_id', 'image_id');
    }
}
