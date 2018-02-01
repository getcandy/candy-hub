<?php

namespace GetCandy\Importers\Aqua\Models\Assets;

use GetCandy\Importers\Aqua\Models\BaseModel;

class ImageLink extends BaseModel
{
    protected $table = 'cscart_images_links';

    public function attributesToArray()
    {
        $ref = $this->ref;
        if (!$ref) {
            $ref = $this->altRef;
        }
        return [
            'url' => url('/aqua_assets/' . $ref->image_path),
            'mime_type' => 'external'
        ];
    }

    public function ref()
    {
        return $this->belongsTo(Image::class, 'detailed_id', 'image_id');
    }

    public function altRef()
    {
        return $this->belongsTo(Image::class, 'image_id', 'image_id');
    }
}
