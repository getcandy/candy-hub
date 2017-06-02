<?php

namespace GetCandy\Api\Pages\Models;

use GetCandy\Api\Channels\Models\Channel;
use GetCandy\Api\Languages\Models\Language;
use GetCandy\Api\Scaffold\BaseModel;

class Page extends BaseModel
{
    protected $hashids = 'language';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'slug', 'seo_title', 'seo_description'
    ];

    /**
     * Get all of the owning element models.
     */
    public function element()
    {
        return $this->morphTo();
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }
}
