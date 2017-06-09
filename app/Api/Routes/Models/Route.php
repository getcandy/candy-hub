<?php

namespace GetCandy\Api\Pages\Models;

use GetCandy\Api\Channels\Models\Channel;
use GetCandy\Api\Languages\Models\Language;
use GetCandy\Api\Layouts\Models\Layout;
use GetCandy\Api\Scaffold\BaseModel;

class Page extends BaseModel
{
    protected $hashids = 'main';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'slug', 'default'
    ];

    /**
     * Get all of the owning element models.
     */
    public function element()
    {
        return $this->morphTo();
    }
}
