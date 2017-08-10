<?php

namespace GetCandy\Api\Tags\Models;

use GetCandy\Api\Scaffold\BaseModel;

class Tag extends BaseModel
{
    protected $hashids = 'tag';

    /**
     * The tags that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    /**
     * Get all of the tags for the post.
     */
    public function taggables()
    {
        return $this->hasMany(Taggable::class);
    }
}