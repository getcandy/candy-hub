<?php

namespace GetCandy\Api\Assets\Models;

use GetCandy\Api\Products\Models\Product;
use GetCandy\Api\Scaffold\BaseModel;

class Asset extends BaseModel
{
    protected $hashids = 'main';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'caption',
        'size',
        'extension',
        'filename',
        'original_filename',
        'sub_kind',
        'width',
        'location',
        'height',
        'kind',
        'position',
        'external'
    ];

    /**
     * Get all of the owning commentable models.
     */
    public function assetable()
    {
        return $this->morphTo();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function source()
    {
        return $this->belongsTo(AssetSource::class, 'asset_source_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function transforms()
    {
        return $this->hasMany(AssetTransform::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function products()
    {
        return $this->morphedByMany(Product::class, 'assetable');
    }

    public function uploader()
    {
        $class = config("assets.upload_drivers.{$this->kind}", config('assets.upload_drivers.file'));
        return new $class;
    }
}
